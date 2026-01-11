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

export function agr (obj: {person?: string, number?: string, gender?: string}): string {
  let out = ''
  if (obj.person) out += obj.person + ' '
  if (obj.number) out += obj.number + ' '
  if (obj.gender) out += obj.gender
  return out.trim()
}
