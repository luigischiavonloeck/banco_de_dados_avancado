const express = require('express')
const { MongoClient } = require('mongodb')
const app = express()
const port = 3000

const url = 'mongodb://localhost:27017'
const dbName = 'termosTi'
const tecnologias = [
  'html',
  'reactjs',
  'node.js',
  'php',
  'angular',
  'sql',
  'javascript'
]

app.get('/', async (req, res) => {
  const client = new MongoClient(url)

  try {
    await client.connect()
    const db = client.db(dbName)
    const collection = db.collection('termos')

    const filter = { Termo: { $in: tecnologias } }
    const options = { sort: { Mensuracao: 1 } }

    const documents = await collection.find(filter, options).toArray()

    const dados = {}
    documents.forEach(document => {
      const anoMes = document.Mensuracao.substr(3, 7)
      if (!dados[anoMes]) {
        dados[anoMes] = {}
      }
      dados[anoMes][document.Termo] = document.Participacao.toFixed(4)
    })

    const dadosSort = Object.keys(dados).sort((a, b) => {
      const dateA = new Date(`01/${a}`)
      const dateB = new Date(`01/${b}`)
      return dateA - dateB
    })

    let html = '<table border="1">'
    html += '<tr><th style="padding: 10px;">Mês/Ano</th>'
    tecnologias.forEach(tecnologia => {
      html += `<th style="padding: 10px;">${tecnologia}</th>`
    })
    html += '</tr>'

    dadosSort.forEach(anoMes => {
      html += '<tr>'
      html += `<td style="padding: 10px;">${anoMes}</td>`
      tecnologias.forEach(tecnologia => {
        const participacao =
          dados[anoMes][tecnologia] != undefined
            ? dados[anoMes][tecnologia]
            : '-'
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
