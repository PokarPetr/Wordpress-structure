import axios from 'axios'

class Note {
    constructor () {
        if (document.querySelector("selector")) {
            axios.defaults.headers.common["X-WP-Nonce"] = projectData.nonce
            this.myVar = document.querySelector("selector")
            this.events()
        }
    }
    events () {
        this.myVar.addEventListener("click", e => this.clickHandler(e))
        document.querySelector(".submit-note").addEventListener("click", () => this.createNote())
    }
    clickHandler(e) {
        if (e.target.classList.contains("delete-note") || e.target.classList.contains("fa-trash-o")) this.deleteNote(e)
        if (e.target.classList.contains("edit-note") || e.target.classList.contains("fa-pencil") || e.target.classList.contains("fa-times")) this.editNote(e)
        if (e.target.classList.contains("update-note") || e.target.classList.contains("fa-arrow-right")) this.updateNote(e)
    }
}
export default Note