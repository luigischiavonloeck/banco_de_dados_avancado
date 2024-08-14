use('Empregos')

//Quantas regiões tinham > de 100 empregos em ti no RS em 2021?
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
