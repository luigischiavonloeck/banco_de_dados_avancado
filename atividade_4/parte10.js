use('Empregos')

db.empregos.aggregate([
  {
    $group: {
      _id: {subsetor:'$subsetor',regiao:'$regiao'},
      totalEmpregos: { $sum: '$empregos' }
    }
  },
  {
    $project: {
        _id: 0,
        regiao: '$_id.regiao',
        setor: '$_id.subsetor',
        totalEmpregos: 1
    }
  },
  {
    $sort: {
        regiao: 1,
        totalEmpregos: -1
    }
  },
  {
    $group: {
        _id: '$regiao',
        subsetor: { $first: '$setor' },
        totalEmpregos: { $first: '$totalEmpregos' }
    }
  }
])
  