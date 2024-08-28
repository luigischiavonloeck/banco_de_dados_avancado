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
      //console.log(ano)
      //console.log(participacoes)
      for (const [termo, valores] of Object.entries(participacoes)) {
        console.log(termo)
        console.log(valores)
        const media = valores.reduce((a, b) => a + b, 0) / valores.length
        if (!medias[termo]) {
          medias[termo] = {}
        }
        medias[termo][ano] = media
      }
    }

    const anos = Object.keys(dados).sort()
    console.log(anos)

    const crescimento = {}
    anos.forEach((ano, index) => {
      if (index === 0) return // Nao compara com o primeiro ano
      const anoAnterior = anos[index - 1]
      for (const [termo, valores] of Object.entries(medias)) {
        if (
          valores[anoAnterior] &&
          valores[ano] &&
          valores[anoAnterior] !== 0
        ) {
          if (!crescimento[ano]) {
            crescimento[ano] = {}
          }
          crescimento[ano][termo] = valores[ano] / valores[anoAnterior]
        }
      }
    })

    let html = '<table border="1" style="border-collapse: collapse;">'
    html +=
      '<tr><th style="padding: 10px;">Ano</th><th style="padding: 10px;">Primeiro</th><th style="padding: 10px;">Segundo</th><th style="padding: 10px;">Terceiro</th></tr>'

    for (const [ano, termos] of Object.entries(crescimento)) {
      const sortedTermos = Object.entries(termos)
        .sort((a, b) => b[1] - a[1])
        .map(entry => [entry[0], entry[1]])

      const primeiro = sortedTermos[0]
        ? `${sortedTermos[0][0]} (${((sortedTermos[0][1] - 1) * 100).toFixed(2)}%)`
        : '-'
      const segundo = sortedTermos[1]
        ? `${sortedTermos[1][0]} (${((sortedTermos[1][1] - 1) * 100).toFixed(2)}%)`
        : '-'
      const terceiro = sortedTermos[2]
        ? `${sortedTermos[2][0]} (${((sortedTermos[2][1] - 1) * 100).toFixed(2)}%)`
        : '-'

      html += `<tr><td style="padding: 10px;">${ano}</td><td style="padding: 10px;">${primeiro}</td><td style="padding: 10px;">${segundo}</td><td style="padding: 10px;">${terceiro}</td></tr>`
    }

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
