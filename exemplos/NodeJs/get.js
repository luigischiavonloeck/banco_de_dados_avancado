import db from "../libs/firebase/rtdb_connection.js"
import {ref, get, child} from "firebase/database"

// const refDB = ref(db)
// const node = "users"
// const refNode = child(refDB,node)

//GET
get(ref(db,'user/-NcX8qCosUwHH-mwMyCf')).then((snapshot)=>{
    if(!snapshot.exists())
      throw new Error("Nó não encontrado")
    console.table(snapshot.val())
    console.log(snapshot.val())
    for(let [key, value] of Object.entries(snapshot.val())){
      console.log("chave: "+key)
      console.log("valore:",value)
    }
}).catch((erro)=>{
    console.log("Erro: "+erro.message)
}).finally(()=>process.exit(0))