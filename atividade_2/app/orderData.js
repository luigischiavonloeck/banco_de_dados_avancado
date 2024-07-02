import * as fb from 'firebase/database'
import { dbConnect } from './connetToFB.js'

dbConnect()
  .then(db => {
    const consulta = fb.query(
      fb.ref(db, 'produtos'),
      fb.orderByChild('id_prod')
    )
    fb.onChildAdded(consulta, snapshot => {
      console.table(snapshot.val())
    })
  })
  .catch(err => console.log(err))
