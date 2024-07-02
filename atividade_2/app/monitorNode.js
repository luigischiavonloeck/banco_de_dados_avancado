import * as fb from 'firebase/database' // sera preciso usar fb antes de cada funcao
import { dbConnect } from './connetToFB.js'

dbConnect()
  .then(db => {
    fb.onChildChanged(fb.ref(db, 'produtos'), snapshot => {
      if (!snapshot.exists()) {
        console.log('Nó não encontrado')
        process.exit(0)
      }
      console.table(snapshot.val())
      if (snapshot.key == '-MwSzyJMlNDToTGtPuhc') {
        console.log(snapshot.key)
        console.log('Remove callback')
        fb.off(fb.ref(db, 'produtos'), 'child_changed')
        throw new Error('Fim da execução')
      }
    })

    // Adiciona um novo produto e atualiza o preco e a quantidade de estoque
    setTimeout(() => {
      let newProduct = {
        descricao: 'Webcam Full HD 1080p com microfone (Preto)',
        id_prod: 127,
        importado: 0,
        nome: 'Webcam 1080p',
        preco: 230.99,
        qtd_estoque: 35
      }
      fb.set(fb.ref(db, `produtos/${newProduct.id_prod}`), newProduct)
    }, 3000)
    setTimeout(() => {
      let newProduct = {
        preco: 179.99,
        qtd_estoque: 255
      }
      fb.update(fb.ref(db, 'produtos/127'), newProduct)
    }, 6000)

    // Atualiza o preco e a quantidade de estoque do produto
    setTimeout(() => {
      let newProduct = {
        preco: 3509,
        qtd_estoque: 7
      }
      fb.update(fb.ref(db, 'produtos/-MwSzyJMlNDToTGtPuhc'), newProduct)
    }, 9000)
  })
  .catch(err => console.log(err))
