const express = require('express')
const { MongoClient } = require('mongodb')
const app = express()
const port = 3000

const url = 'mongodb://localhost:27017'
const dbName = 'termosTi'

const tecnologias = {
  python: ['ruby', 'javascript'],
  go: ['rust', 'elixir'],
  shell: ['powershell', 'zsh']
}

app.get('/', async (req, res) => {
  const client = new MongoClient(url)

  try {
    await client.connect()
    const db = client.db(dbName)
    const collection = db.collection('termos')

    const filter = {
      Termo: {
        $in: Object.keys(tecnologias).concat(...Object.values(tecnologias))
      }
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
    console.log(dados)

    const medias = {}
    for (const [ano, participacoes] of Object.entries(dados)) {
      console.log(ano)
      //console.log(participacoes)
      for (const [termo, valores] of Object.entries(participacoes)) {
        console.log(termo)
        //console.log(valores)
        const media = valores.reduce((a, b) => a + b, 0) / valores.length
        if (!medias[termo]) {
          medias[termo] = {}
        }
        medias[termo][ano] = media.toFixed(3)
      }
    }

    let html = '<table border="1" style="border-collapse: collapse;">'
    html += '<tr><th>Ano</th>'
    for (const [tecnologia, concorrentes] of Object.entries(tecnologias)) {
      html += `<th style="padding: 10px; margin-left: 15px">${tecnologia}</th>`
      concorrentes.forEach(concorrente => {
        html += `<th style="padding: 10px;">${concorrente}</th>`
      })
      html += '<th style="padding: 10px;"></th>'
    }
    html += '</tr>'

    const anos = Object.keys(dados).sort()
    anos.forEach(ano => {
      html += '<tr>'
      html += `<td style="padding: 10px;">${ano}</td>`
      for (const [tecnologia, concorrentes] of Object.entries(tecnologias)) {
        const media =
          medias[tecnologia] && medias[tecnologia][ano] !== undefined
            ? medias[tecnologia][ano]
            : '-'
        html += `<td style="padding: 10px;">${media}</td>`
        concorrentes.forEach(concorrente => {
          const mediaConcorrente =
            medias[concorrente] && medias[concorrente][ano] !== undefined
              ? medias[concorrente][ano]
              : '-'
          html += `<td style="padding: 10px;">${mediaConcorrente}</td>`
        })
        html += '<td style="padding: 10px;"></td>'
      }
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
