import * as fb from 'firebase/database'
import { dbConnect } from './connetToFB.js'

dbConnect()
  .then(db => {
    const consulta = fb.query(
      fb.ref(db, 'produtos'), 
      fb.orderByChild('preco')
    )
    fb.onChildAdded(consulta, snapshot => {
      console.table(snapshot.val())
    })
  })
  .catch(err => console.log(err))
