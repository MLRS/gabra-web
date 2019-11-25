interface Entry {
  key?: string
  en: string
  mt: string
}

declare module '@/assets/data/i18n.yaml' {
  var es: Entry[]
  export default es
}
