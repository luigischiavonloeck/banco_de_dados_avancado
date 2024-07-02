import * as fb from 'firebase/database'
import { dbConnect } from './connetToFB.js'

dbConnect().then(db => {
  //console.log(db)

  fb.get(fb.ref(db, 'produtos/'))
    .then(snapshot => {
      if (!snapshot.exists()) throw new Error('Nó não encontrado')
      console.table(snapshot.val())
      for (let [key, value] of Object.entries(snapshot.val())) {
        console.log('Chave: ' + key)
        console.log('Objeto:', value)
      }
    })
    .catch(erro => {
      console.log('Erro: ' + erro.message)
    })
    .finally(() => process.exit(0))
})
