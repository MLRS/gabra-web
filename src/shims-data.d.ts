interface Entry {
  key?: string
  en: string
  mt: string
}

declare module '@/assets/data/i18n.yaml' {
  const es: Entry[]
  export default es
}

interface Lexeme {
  _id: string
  lemma: string
  pos: string
  derived_form?: number
  glosses?: {
    gloss: string,
    examples: {
      example: string,
      type: 'full' | 'short'
    }[]
  }[]
  alternatives?: string[]
  root?: Root
  frequency?: string
}

interface Wordform {
  surface_form: string
  generated?: boolean
  alternatives?: string[]

  number?: string
  gender?: string
  aspect?: string
  subject?: Agreement
  dir_obj?: Agreement
  ind_obj?: Agreement
  polarity?: Polarity
}

type Polarity = 'pos' | 'neg'

interface Agreement {
  person: 'p1' | 'p2' | 'p3'
  number: 'sg' | 'pl'
  gender?: 'm' | 'f'
}

interface Root {
  radicals: string
  variant?: number
  type: string
}

interface Source {
  key: string
  author: string
  title: string
  year: number
  note: string
}
