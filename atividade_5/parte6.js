const express = require('express')
const { MongoClient } = require('mongodb')
const app = express()
const port = 3000
const url = 'mongodb://localhost:27017'
const dbName = 'termosTi'

const databases = ['reactjs', 'node.js', 'laravel', 'angular', 'vue.js']

app.get('/', async (req, res) => {
  const client = new MongoClient(url)

  try {
    await client.connect()
    const db = client.db(dbName)
    const collection = db.collection('termos')

    const filter = {
      Termo: { $in: databases }
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

    const chartData = {
      labels: anos,
      datasets: databases.map(db => ({
        label: db,
        data: anos.map(ano => medias[db][ano] || 0),
        fill: false,
        borderColor: getRandomColor(),
        tension: 0.1
      }))
    }

    const html = `
      <!DOCTYPE html>
      <html>
      <head>
        <title>Database Performance</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      </head>
      <body>
        <h1>Tecnologias e suas Performace entre os Anos</h1>
        <canvas id="myChart" width="400" height="200"></canvas>
        <script>
          const ctx = document.getElementById('myChart').getContext('2d');
          const chartData = ${JSON.stringify(chartData)};
          const myChart = new Chart(ctx, {
            type: 'line',
            data: chartData,
            options: {
              scales: {
                x: {
                  title: {
                    display: true,
                    text: 'Ano'
                  }
                },
                y: {
                  title: {
                    display: true,
                    text: 'Participação (%)'
                  }
                }
              }
            }
          });
        </script>
      </body>
      </html>
    `
    res.send(html)
  } catch (err) {
    console.error(err)
    res.status(500).send('Erro ao conectar ao banco de dados')
  } finally {
    await client.close()
  }
})

function getRandomColor() {
  const letters = '0123456789ABCDEF'
  let color = '#'
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)]
  }
  return color
}

app.listen(port, () => {
  console.log(`Servidor rodando em http://localhost:${port}`)
})
