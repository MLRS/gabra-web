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
}

interface Wordform {
  surface_form: string
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
