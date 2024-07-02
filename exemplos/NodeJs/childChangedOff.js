import { off, onChildChanged, ref } from "firebase/database";
import db from "../libs/firebase/rtdb_connection.js"

const node = "user"
//CHILD ADDED
let refDB = ref(db, node);

onChildChanged(refDB, (snapshot) => { //()=>{}
  if (!snapshot.exists()) {
    console.log("Nó não encontrado")
    process.exit(0)
  }
  console.table(snapshot.val())

  if (snapshot.key == 4) {
    console.log(snapshot.key)
    console.log("Remove callback")
    off(refDB, 'child_changed')
  }
});
