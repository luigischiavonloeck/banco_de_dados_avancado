import { onChildChanged, ref, update } from "firebase/database";
import db from "../libs/firebase/rtdb_conection.js"

const node = "users"
//CHILD ADDED
let count =0;
let refDB = ref(db, node);

onChildChanged(refDB, (snapshot) => { //()=>{}
  if (!snapshot.exists()) {
    console.log("Nó não encontrado")
    process.exit(0)
  }
  console.table(snapshot.val())
});

/**
 * Daqui a 1s haverá uma atualização no nó users/3
 * fazendo com que o callback do evendo child_changed seja
 * executado, mostrando o nó modificado.
 *
 **/

setTimeout(() => {
  update(refDB, {
    "3": {
      email: "gillgonzales@ifsul.edu.br",
      nome: `Prof. Gill`,
    }
  }).then(() => {
    console.log('Updated!')
  })
}, 1000)