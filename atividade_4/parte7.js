use('Empregos')

//Qual as 5 subclasse emprega mais em TI no estado?
db.empregos.aggregate([
  {
    $group: {
      _id: '$subsetor',
      totalEmpregos: { $sum: '$empregos' }
    }
  },
  {
    $sort: {
      totalEmpregos: -1
    }
  },
  {
    $limit: 5
  }
])
