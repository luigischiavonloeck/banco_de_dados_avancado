const express = require('express')
const { MongoClient } = require('mongodb')
const app = express()
const port = 3000

const url = 'mongodb://localhost:27017'
const dbName = 'termosTi'
const tecnologias = ['python', 'go', 'shell']

app.get('/', async (req, res) => {
  const client = new MongoClient(url)

  try {
    await client.connect()
    const db = client.db(dbName)
    const collection = db.collection('termos')

    const filter = {
      Termo: { $in: tecnologias },
      Mensuracao: { $regex: '^01/' } // Considera apenas o primeiro mês de cada ano
    }
    const options = { sort: { Mensuracao: 1 } }

    const documents = await collection.find(filter, options).toArray()

    const dados = {}
    documents.forEach(document => {
      const ano = document.Mensuracao.substr(-4)
      if (!dados[ano]) {
        dados[ano] = {}
      }
      dados[ano][document.Termo] = document.Participacao.toFixed(3)
    })

    const dadosSort = Object.keys(dados).sort()

    let html = '<table border="1">'
    html += '<tr><th style="padding: 10px;">Ano</th>'
    tecnologias.forEach(tecnologia => {
      html += `<th style="padding: 10px;">${tecnologia}</th>`
    })
    html += '</tr>'

    dadosSort.forEach(ano => {
      html += '<tr>'
      html += `<td style="padding: 10px;">${ano}</td>`
      tecnologias.forEach(tecnologia => {
        const participacao =
          dados[ano][tecnologia] != undefined ? dados[ano][tecnologia] : '-'
        html += `<td style="padding: 10px;">${participacao}</td>`
      })
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
