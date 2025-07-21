import markdownit from 'markdown-it'

// TODO localise
// import { useRootStore } from '@/stores/root'
// const store = useRootStore()

import terms from '@/assets/data/i18n.yaml'

const MarkdownIt = markdownit({
  html: true
})

export type Language = 'en' | 'mt'

// Standalone i18n function with explicit language
export function __l (lang: Language, key: string, replacements?: {[key:string]: string} | string[]): string {
  const f = terms.find((x: Entry) => {
    if (x.key) {
      return x.key === key
    } else {
      return x.en === key
    }
  })
  if (f) {
    let s = f[lang]
    if (!s) {
      if (import.meta.env.DEV) {
        console.error(`No localisation of key '${key}' in language '${lang}'`)
      }
      return key
    }
    if (replacements) {
      for (const key in replacements) { // works for both key-values and arrays
        s = s.replace(`{${key}}`, (replacements as {[key:string]:string})[key])
      }
    }
    return s
  } else {
    if (import.meta.env.DEV) {
      console.error(`No localisation of key '${key}'`)
    }
    return key
  }
}

// Get text for key
// Can only be called from within component
export function __(key: string, replacements?: {[key:string]: string} | string[]): string {
  // return __l(store.language, key, replacements)
  return __l('en', key, replacements)
}

// Render as Markdown
export function markdown(md: string): string {
  return MarkdownIt.render(md)
}

// Render as Markdown (inline)
export function markdownInline(md: string): string {
  return MarkdownIt.renderInline(md)
}
