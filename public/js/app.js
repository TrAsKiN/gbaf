const formComment = document.querySelector('#form-comment')
const buttonComment = document.querySelector('#toggle-new-comment')

formComment.classList.toggle('hidden')

buttonComment.addEventListener('click', function (e) {
    e.preventDefault()
    formComment.classList.remove('hidden')
})
