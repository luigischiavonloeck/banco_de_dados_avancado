use('Empregos')

db.empregos.aggregate([
  {
    $match: {
      ano: { $in: [2020, 2021] }
    }
  },
  {
    $group: {
      _id: { regiao: '$regiao', ano: '$ano' },
      totalEmpregos: { $sum: '$empregos' }
    }
  },
  {
    $group: {
      _id: '$_id.regiao',
      empregos2020: {
        $sum: {
          $cond: {if:{ $eq: ['$_id.ano', 2020] }, then: '$totalEmpregos', else: 0}
        }
      },
      empregos2021: {
        $sum: {
          $cond: {if:{ $eq: ['$_id.ano', 2021] }, then: '$totalEmpregos', else: 0}
        }
      }
    }
  },
  {
    $project: {
      regiao: '$_id',
      crescimentoPercentual: {
        $cond: {if:
          { $eq: ['$empregos2020', 0] },
          then:0,
          else:{
            $multiply: [
              {
                $divide: [
                  { $subtract: ['$empregos2021', '$empregos2020'] },
                  '$empregos2020'
                ]
              },
              100
            ]
          }
        }
      }
    }
  },
  {
    $sort: { crescimentoPercentual: -1 }
  },
  {
    $limit: 1
  }
])
