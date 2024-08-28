use('Empregos')
// Não consegui fazer esse desafio
db.empregos.aggregate([
  {
    $match: {
      ano: 2021,
    }
  },
  {
    $lookup: {
      from: 'municipios',
      localField: 'regiao',
      foreignField: 'cidade',
      as: 'municipio'
    }
  },
  {
    $unwind: '$municipio'
  },
  {
    $lookup: {
      from: 'populacao',
      localField: 'municipio.cidade',
      foreignField: 'municipio',
      as: 'populacao'
    }
  },
  {
    $unwind: '$populacao'
  },
  {
    $group: {
      _id: '$regiao',
      totalEmpregos: { $sum: '$empregos' },
      totalPopulacao: { $sum: '$populacao.população' }
    }
  }
])
