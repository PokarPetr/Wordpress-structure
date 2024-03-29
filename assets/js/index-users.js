

class User {
    constructor() {
        if (document.querySelector("#users_form")) {
            this.form = document.querySelector("#users_form")
            this.events()
        }
    }
    events() {
        this.form.addEventListener("click", (e) => this.clickHandler(e))
    }
    clickHandler(e) {
        if (e.target.classList.contains("save")) this.create(e)
        if (e.target.classList.contains("save-user-data")) this.saveEditedUser(e)
        if (e.target.id === 'add_user_button') this.change_create_user_form_visibility()

        if (e.target.classList.contains("edit-note") || e.target.classList.contains("fa-pencil")) this.edit(e)
        if (e.target.classList.contains("delete-note") || e.target.classList.contains("fa-trash-o")) this.delete(e)
        if (e.target.classList.contains("cancel") || e.target.classList.contains("fa-times")) this.cancel(e)
        if (e.target.classList.contains("cancel-note")) this.cancelEdit(e)
    }
    clearValueById(id) {
        document.getElementById(id).value = '';
    }

    change_create_user_form_visibility(){
        let div_create_user = document.querySelector('.create-user');
        let container = document.querySelector('.container');
        if (div_create_user) {
            div_create_user.classList.toggle('d_none');
        }
        container.scrollIntoView();
    }

    cancel(element) {
        element.preventDefault();
        if (confirm("Вы уверены?")) {
            this.clearValueById('user__login');
            this.clearValueById('display_name');
            this.clearValueById('user__email');
            this.clearValueById('user__password');
            this.change_create_user_form_visibility()
        } else {
            console.log("Вы отменили действие.");
        }
    }

    delete(element) {
        element.preventDefault();


        if (confirm("Are you sure you want to delete the user?")) {
            let user_id = element.target.getAttribute('data-id')
            let delete_note =  document.querySelector('.delete-note[data-id="' + user_id + '"]');
            const form = new FormData();
            form.append('user_id', user_id);
            form.append('nonce', projectData.nonce);
            form.append('action', 'delete_user');
            delete_note.innerHTML = 'Deleting...'
            let resp = fetch(projectData.ajax_url,
                {
                    method: 'POST',
                    body: form
                });
            resp.then(response => {
                console.log(response);
                if  (response.ok) {
                    delete_note.innerHTML = 'Deleted'
                    alert('User is deleted');
                    window.location.href = '/users';
                }
            }).catch(error => {
                console.error("Error:", error);
            });
        } else {
            console.log("You canceled the deletion.");
        }
    }

    create(element) {
        element.preventDefault();
        let save_button = document.querySelector('.save')
        let user_login = document.getElementById(`user__login`).value
        let display_name = document.getElementById(`display_name`).value
        let user_email = document.getElementById(`user__email`).value
        let user_password = document.getElementById(`user__password`).value

        const form = new FormData();
        form.append('user_login', user_login);
        form.append('display_name', display_name);
        form.append('user_email', user_email);
        form.append('user_password', user_password);
        form.append('nonce', projectData.nonce);
        form.append('action', 'create-user');
        save_button.innerHTML = 'Saving...';
        let resp = fetch(projectData.ajax_url,
            {
                method: 'POST',
                body: form
            });
        resp.then(response => {
            console.log(response);
            if  (response.ok) {
                save_button.innerHTML = 'Saved';
                window.location.href = '/users';
            }
        }).catch(error => {
            console.error("Error:", error);
        });
    }

    saveEditedUser(element){
        element.preventDefault();
        let user_id = element.target.getAttribute('data-id')
        let user_nickname = document.getElementById(`${user_id}__login`).value
        let user_email = document.getElementById(`${user_id}__email`).value
        let user_password = document.getElementById(`${user_id}__password`).value

        let user_show_nickname = document.getElementById(`${user_id}show__login`)
        let user_show_email = document.getElementById(`${user_id}show__email`)



        const form = new FormData();
        form.append('user_id', user_id);
        form.append('nickname', user_nickname);
        form.append('email', user_email);
        form.append('password', user_password);
        form.append('nonce', projectData.nonce);
        form.append('action', 'form_handler');

        element.target.innerHTML = 'Saving...'
        let resp = fetch(projectData.ajax_url,
            {
                method: 'POST',
                body: form
            });
        resp.then(response => {
            if  (response.ok) {
                console.log(response);
                element.target.innerHTML = 'Saved'
                user_show_nickname.innerHTML = user_nickname
                user_show_email.innerHTML = user_email

                this.cancelEdit(element)
                // window.location.href = '/users';
            }
        }).catch(error => {
            console.error("Error:", error);
        });

    }
    changeInputVisible(id){
        let user_id = id
        let user_nickname = document.getElementById(`${user_id}__login`)
        user_nickname.classList.toggle('d_none')
        let user_email = document.getElementById(`${user_id}__email`)
        user_email.classList.toggle('d_none')
        let user_password = document.getElementById(`${user_id}__password`)
        user_password.classList.toggle('d_none')

        let user_nickname_show = document.getElementById(`${user_id}show__login`)
        user_nickname_show.classList.toggle('d_none')
        let user_email_show = document.getElementById(`${user_id}show__email`)
        user_email_show.classList.toggle('d_none')
        let user_password_show = document.getElementById(`${user_id}show__password`)
        user_password_show.classList.toggle('d_none')

    }
    edit(element) {
        element.preventDefault();
        let user_id = element.target.getAttribute('data-id')
        this.changeInputVisible(user_id)

        element.target.classList.toggle('edit-note')
        element.target.classList.toggle('save-user-data')

        let delete_note =  document.querySelector('.delete-note[data-id="' + user_id + '"]');

        if (element.target.classList.contains("save-user-data")) {
            element.target.innerHTML = 'Save'
        } else {
            element.target.innerHTML = 'Edit'
        }
        if (delete_note) {
            let icon = delete_note.querySelector('i')
            icon.classList.remove('fa-trash-o')

            delete_note.innerHTML = 'Cancel'
            delete_note.classList.toggle('cancel-note')
            delete_note.classList.toggle('delete-note')
        }

    }
    cancelEdit(element) {

        let user_id = element.target.getAttribute('data-id')
        let edit_note =  document.querySelector('.save-user-data[data-id="' + user_id + '"]');
        let cancel_note =  document.querySelector('.cancel-note[data-id="' + user_id + '"]');
        this.changeInputVisible(user_id)

        if(cancel_note) {
            cancel_note.classList.toggle('delete-note')
            cancel_note.classList.toggle('cancel-note')
            cancel_note.innerHTML = '<i class="fa fa-trash-o" aria-hidden="true"></i> Delete'
        }

        if (edit_note) {
            edit_note.innerHTML = '<i class="fa fa-pencil" aria-hidden="true"></i> Edit'
            edit_note.classList.toggle('save-user-data')
            edit_note.classList.toggle('edit-note')
        }

    }
}
const us = new User()



