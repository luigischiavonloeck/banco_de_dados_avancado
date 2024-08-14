use('Empregos')

//Quantos tecnólogos em TI havia no RS por ano?
db.empregos.aggregate([
  {
    $group: {
      _id: '$ano',
      total: { $sum: '$empregos' }
    }
  },
  {
    $sort: {
      _id: 1
    }
  }
])
