<template>
    <div>
        <div class="alert" v-if="invalidCredentials">
            <span class="clasebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            Les informations d'identification invalides
        </div>
        <validation-errors v-if="validationErrors" :errors="validationErrors"></validation-errors>
        <div class="forgot-page">
            <form class="form">
                <my-input type="text" placeholder="email address" v-model="user.email"/>
                <my-button type="submit" @click.prevent="sendForgotPassword">Send email</my-button>
                <router-link to="/login">
                    <p class="message">Aller à la page de connexion ?<a href="#"> s'identifier</a></p>
                </router-link>
            </form>
        </div>
    </div>
</template>

<script>
    import { mapActions, mapGetters } from 'vuex';

    export default {
        name: "ForgotPassword",

        data: () => ({
            user: {
                email:"",
            }
        }),

        created() {
            this.checkUserState();
        },

        computed: {
            ...mapGetters({
                invalidCredentials: 'auth/invalidCredentials',
                validationErrors: 'auth/errors'
            })
        },

        methods: {

            ...mapActions({
                checkUserState: 'auth/setLoggedInstate',
                forgotPassword: 'auth/forgotPassword',
            }),

            sendForgotPassword() {
                this.$store.dispatch('auth/forgotPassword', this.user)
            },

            checkUserState() {
                this.$store.dispatch('auth/setLoggedInstate', this.user)
            }

        },

    }
</script>

<style lang="scss">
    .forgot-page{
        width: 360px;
        padding: 8% 0 0;
        margin: auto;
        display: flex;
    }

    .alert {
        padding: 20px;
        background-color: #f44336;
        color: white;
        margin-bottom: 15px;
    }

    .closebtn {
        margin-left: 15px;
        color: white;
        font-weight: bold;
        float: right;
        font-size: 22px;
        line-height: 20px;
        cursor: pointer;
        transition: 0.3s;
    }

    .closebtn:hover {
        color: black;
    }

</style>
