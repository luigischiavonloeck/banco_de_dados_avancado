import * as fb from 'firebase/database'
import { dbConnect } from './connetToFB.js'

dbConnect()
  .then(db => {
    const consulta = fb.query(
      fb.ref(db, 'produtos'),
      fb.orderByChild('id_prod')
    )
    let products = []
    fb.onValue(consulta, snapshot => {
      snapshot.forEach(child => {
        products.push(child.val())
      })
      products.reverse().forEach(product => {
        console.table(product)
      })
    })
  })
  .catch(err => console.log(err))
