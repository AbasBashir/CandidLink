
document.addEventListener('DOMContentLoaded', function () {
    const toggleButtons = document.querySelectorAll('.toggle-reply');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function () {

            const postId = this.dataset.id;
            const replyForm = document.querySelector(`#post-reply-${postId}`);

            if (replyForm) {
                replyForm.classList.toggle('visible');
            }
        });
    });
});