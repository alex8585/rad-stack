export function imgUrlFromFile(file) {
  const urlCreator = window.URL || window.webkitURL
  const imageUrl = urlCreator.createObjectURL(file)
  return imageUrl
}
export function stripHtml(html) {
  const temporalDivElement = document.createElement('div')
  temporalDivElement.innerHTML = html
  return temporalDivElement.textContent || temporalDivElement.innerText || ''
}

export function stripTags(str) {
  return str.replace(/<\/?[^>]+(>|$)/g, '')
}
export function shorten(str, no_words, suff = ' ...') {
  let newStr = str.split(' ').splice(0, no_words).join(' ')
  newStr = stripHtml(newStr)
  newStr = stripTags(newStr)

  if (str.length > newStr.length) {
    newStr += suff
  }
  return newStr
}
