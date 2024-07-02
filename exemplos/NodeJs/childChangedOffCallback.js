import { off, onChildChanged, ref } from "firebase/database";
import db from "../libs/firebase/rtdb_conection.js"

const node = "users"
//CHILD ADDED
let refNode = ref(db, node);

const callback = function(snapshot){ //()=>{}
  if (!snapshot.exists()) {
    console.log("Nó não encontrado")
    process.exit(0)
  }
  console.table(snapshot.val())

  if (snapshot.key == 4) {
    console.log(snapshot.key)
    console.log("Remove callback")
    off(refNode, 'child_changed',callback)
  }
}

onChildChanged(refNode, callback);

onChildChanged(refNode, (snapshot) =>{
  if (!snapshot.exists()) {
    console.log("Nó não encontrado")
    process.exit(0)
  }
  console.log(snapshot.val())
})
 
