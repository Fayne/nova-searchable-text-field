import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'
import PreviewField from './components/PreviewField'

Nova.booting((app, store) => {
  app.component('index-searchable-text-field', IndexField)
  app.component('detail-searchable-text-field', DetailField)
  app.component('FormSearchableTextField', FormField)
  // app.component('preview-searchable-text-field', PreviewField)
})
