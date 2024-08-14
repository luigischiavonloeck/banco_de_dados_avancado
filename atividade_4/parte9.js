use('Empregos')

db.empregos.aggregate([
  {
    $match: {
      regiao: 'Pelotas'
    }
  },
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
    $limit: 1
  }
])
