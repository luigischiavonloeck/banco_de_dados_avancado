use('Empregos')

db.empregos.aggregate([
  {
    $match: {
      ano: 2021
    }
  },
  {
    $group: {
      _id: '$regiao',
      totalEmpregos: { $sum: '$empregos' }
    }
  },
  {
    $match: {
      totalEmpregos: { $gt: 100 }
    }
  },
  {
    $count: 'regioes'
  }
])
