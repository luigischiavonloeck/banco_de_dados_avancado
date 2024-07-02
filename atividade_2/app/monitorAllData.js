import * as fb from 'firebase/database'
import { dbConnect } from './connetToFB.js'

dbConnect()
  .then(db => {
    fb.onValue(fb.ref(db, 'produtos'), snapshot => {
      console.table(snapshot.val())
    })
    setTimeout(() => {
      let newProduct = {
        descricao: 'Apple iPhone 14 (128 GB) - Estelar',
        id_prod: 126,
        importado: 0,
        nome: 'iPhone 14',
        preco: 4000,
        qtd_estoque: 5
      }
      fb.push(fb.ref(db, 'produtos/'), newProduct)
    }, 5000)
  })
  .catch(err => console.log(err))
