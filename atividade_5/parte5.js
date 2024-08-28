const express = require('express')
const { MongoClient } = require('mongodb')
const app = express()
const port = 3000

const url = 'mongodb://localhost:27017'
const dbName = 'termosTi'

const backend = ['node.js', 'django', 'flask', 'spring', 'laravel']
const frontend = ['reactjs', 'angular', 'vue.js', 'svelte', 'ember.js']

app.get('/', async (req, res) => {
  const client = new MongoClient(url)

  try {
    await client.connect()
    const db = client.db(dbName)
    const collection = db.collection('termos')

    const filter = {
      Termo: { $in: backend.concat(frontend) }
    }
    const options = { sort: { Mensuracao: 1 } }

    const documents = await collection.find(filter, options).toArray()

    const dados = {}
    documents.forEach(document => {
      const ano = document.Mensuracao.substr(-4)
      if (!dados[ano]) {
        dados[ano] = {}
      }
      if (!dados[ano][document.Termo]) {
        dados[ano][document.Termo] = []
      }
      dados[ano][document.Termo].push(document.Participacao)
    })

    const medias = {}
    for (const [ano, participacoes] of Object.entries(dados)) {
      for (const [termo, valores] of Object.entries(participacoes)) {
        const media = valores.reduce((a, b) => a + b, 0) / valores.length
        if (!medias[termo]) {
          medias[termo] = {}
        }
        medias[termo][ano] = media
      }
    }

    const anos = Object.keys(dados).sort()

    const somatorio = {}
    anos.forEach(ano => {
      somatorio[ano] = { backend: 0, frontend: 0 }
      for (const [termo, valores] of Object.entries(medias)) {
        if (backend.includes(termo)) {
          somatorio[ano].backend += valores[ano] || 0
        } else if (frontend.includes(termo)) {
          somatorio[ano].frontend += valores[ano] || 0
        }
      }
    })

    let html = '<table border="1" style="border-collapse: collapse;">'
    html +=
      '<tr><th style="padding: 10px;">Ano</th><th style="padding: 10px;">Backend</th><th style="padding: 10px;">Frontend</th></tr>'

    anos.forEach(ano => {
      html += `<tr><td style="padding: 10px;">${ano}</td>`
      html += `<td style="padding: 10px;">${somatorio[ano].backend.toFixed(
        2
      )}</td>`
      html += `<td style="padding: 10px;">${somatorio[ano].frontend.toFixed(
        2
      )}</td>`
      html += '</tr>'
    })

    html += '</table>'
    res.send(html)
  } catch (err) {
    console.error(err)
    res.status(500).send('Erro ao conectar ao banco de dados')
  } finally {
    await client.close()
  }
})

app.listen(port, () => {
  console.log(`Servidor rodando em http://localhost:${port}`)
})
