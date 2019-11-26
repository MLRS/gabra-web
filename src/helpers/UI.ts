export function derivedForm (dform: number): string {
  switch (dform) {
    case 1: return 'I'
    case 2: return 'II'
    case 3: return 'III'
    case 4: return 'IV'
    case 5: return 'V'
    case 6: return 'VI'
    case 7: return 'VII'
    case 8: return 'VIII'
    case 9: return 'IX'
    case 10: return 'X'
    default: return ''
  }
}

export function agr (item: any, field: string): string {
  let out = ''
  if (item[field]) {
    let agr = item[field]
    if (agr.person) out += agr.person + ' '
    if (agr.gender) out += agr.gender + '. '
    if (agr.number) out += agr.number + '. '
  }
  return out
}
