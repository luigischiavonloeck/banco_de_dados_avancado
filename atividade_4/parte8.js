use('Empregos')

//Qual subclasses emprega mais em TI na microrregião de Pelotas?
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
