$(function () {

    var baseUrl = '../rest/v1/Me';

    Vue.use(VueI18n);

    var language = 'en';
    if (navigator.language) {
        language = navigator.language.substring(0, 2);
    }
    var messages = {
        en: {
            ACTIONS: 'Actions',
            CANCEL: 'Cancel',
            DELETE: 'Delete',
            DISPLAY_NAME: 'Display Name',
            DISPLAY_NAME_HELP: 'First- and Lastname',
            EMAIL: 'E-Mail',
            EMAIL_HELP: 'Unique E-Mail Address',
            PASSWORD: 'Password',
            PASSWORD_CONFIRM: 'Password Confirmation',
            REFRESH: 'Refresh',
            SAVE: 'Save',
            USER_CREATE: 'Create User',
            USER_DELETE: 'Delete User {0}',
            USER_DELETE_CONFIRM: 'Do you really want to delete user {0} {1}?',
            USER_EDIT: 'Edit User {0}',
            USER_ID: 'User ID',
            USER_ID_HELP: 'Alphanumeric characters and unique',
            USER_UPLOAD: 'Upload Users',
            count: "Showing {from} to {to} of {count} users|{count} users|One user",
            first: 'First',
            last: 'Last',
            filter: "Filter:",
            filterPlaceholder: "Search query",
            limit: "Users:",
            page: "Page:",
            noResults: "No user found",
            filterBy: "Filter by {column}",
            loading: 'Loading...',
            defaultOption: 'Select {column}',
            columns: 'Columns',
            EXCEPTION_INVALID_ID: 'User ID invalid',
            EXCEPTION_INVALID_EMAIL: 'E-Mail address invalid',
            EXCEPTION_INVALID_PASSWORD: 'Password invalid',
            EXCEPTION_INVALID_VALUE: 'Duplicate value',
            EXCEPTION_DUPLICATE_EMAIL: 'E-Mail address already existing',
            EXCEPTION_ENTRY_ALREADY_EXIST: 'Entry already existing',
            EXCEPTION_ENTRY_NOT_EXIST: 'Entry not existing',
            EXCEPTION_ENTRY_READONLY: 'Entry readonly',
            EXCEPTION_PROVIDER_NOT_EXIST: 'Provider not existing',
            EXCEPTION_AUTHENTICATION_FAILED: 'Authentication failed'
        },
        de: {
            ACTIONS: 'Aktionen',
            CANCEL: 'Abbrechen',
            DELETE: 'Löschen',
            DISPLAY_NAME: 'Anzeigename',
            DISPLAY_NAME_HELP: 'Vor- und Nachname',
            EMAIL: 'E-Mail',
            EMAIL_HELP: 'Eindeutige E-Mail-Adresse',
            PASSWORD: 'Passwort',
            PASSWORD_CONFIRM: 'Password Wiederholung',
            REFRESH: 'Neu laden',
            SAVE: 'Speichern',
            USER_CREATE: 'Benutzer anlegen',
            USER_DELETE: 'Benutzer {0} löschen',
            USER_DELETE_CONFIRM: 'Wollen Sie wirklich Benutzer {0} {1} löschen?',
            USER_EDIT: 'Benutzer {0} ändern',
            USER_ID: 'Benutzer ID',
            USER_ID_HELP: 'Alphanumerische Zeichen und eindeutig',
            USER_UPLOAD: 'Benutzer hochladen',
            count: "Anzeige {from} bis {to} von {count} Benutzern|{count} Benutzer|Ein Benutzer",
            first: 'Erster',
            last: 'Letzter',
            filter: "Benutzer filtern:",
            filterPlaceholder: "Suchbegriff",
            limit: "Benutzer:",
            page: "Seite:",
            noResults: "Keinen Benutzer gefunden",
            filterBy: "Filtern mit {column}",
            loading: 'Laden...',
            defaultOption: 'Selektiere {column}',
            columns: 'Spalte',
            EXCEPTION_INVALID_ID: 'Benutzer ID ungültig',
            EXCEPTION_INVALID_EMAIL: 'E-Mail Adresse ungültig',
            EXCEPTION_INVALID_PASSWORD: 'Password ungültig',
            EXCEPTION_INVALID_VALUE: 'Doppelter Wert',
            EXCEPTION_DUPLICATE_EMAIL: 'E-Mail Adresse existiert bereits',
            EXCEPTION_ENTRY_ALREADY_EXIST: 'Eintrag existiert bereits',
            EXCEPTION_ENTRY_NOT_EXIST: 'Eintrag existiert nicht',
            EXCEPTION_ENTRY_READONLY: 'Eintrag schreibgeschützt',
            EXCEPTION_PROVIDER_NOT_EXIST: 'Provider existiert nicht',
            EXCEPTION_AUTHENTICATION_FAILED: 'Anmeldung fehlgeschlagen'
        }
    };

    var i18n = new VueI18n({
        locale: language,
        fallbackLocale: 'en',
        messages
    });

    Vue.use(VeeValidate, {
        // events: '',
        //i18nRootKey: 'validations',
        i18n,
        dictionary: {
            en: {
                messages: __vee_validate_locale__en.js.messages,
                attributes: messages.en
            },
            de: {
                messages: __vee_validate_locale__de.js.messages,
                attributes: messages.de
            }
        }
    });

    var userLogin = new Vue({
        el: "#userLogin",
        data: {
            id: '',
            password: '',
            alert: ''
        },
        methods: {
            login: function(){
                this.$validator.validate().then(function(result){
                    if (!result) {
                        // do stuff if not valid.
                    } else {
                        $.ajax({
                            url: baseUrl + '/' + 'login',
                            method: 'POST',
                            data: {
                                id: userLogin.id,
                                password: userLogin.password
                            }
                        }).done(function(data, textStatus, jqXHR){
                            //location.reload();
                            alert("login successfull");
                        }).fail(function(data, textStatus, jqXHR){
                            userLogin.$data.alert = i18n.t(data.responseText);
                            $('#userLoginAlert').removeClass('d-none');
                        });
                    }
                });
            },
            logout: function(){
                this.$validator.validate().then(function(result){
                    if (!result) {
                        // do stuff if not valid.
                    } else {
                        $.ajax({
                            url: baseUrl + '/' + 'logout',
                            method: 'POST',
                            data: {
                                // id: userLogin.id,
                                // password: userLogin.password
                            }
                        }).done(function(data, textStatus, jqXHR){
                            //location.reload();
                            alert("logout successfull");
                        }).fail(function(data, textStatus, jqXHR){
                            userLogin.$data.alert = i18n.t(data.responseText);
                            $('#userLoginAlert').removeClass('d-none');
                        });
                    }
                });
            },
            hideAlert: function(){
                $('#userLoginAlert').addClass('d-none');
            }
        },
        i18n: i18n
    });

});