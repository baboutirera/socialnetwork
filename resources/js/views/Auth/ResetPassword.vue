<template>
    <div>
        <div class="alert" v-if="invalidCredentials">
            <span class="clasebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            Les informations d'identification invalides
        </div>
        <validation-errors v-if="validationErrors" :errors="validationErrors"></validation-errors>
        <div class="reset-page">
            <form class="form">
                <my-input type="text" placeholder="email address" v-model="user.email"/>
                <my-input type="text" placeholder="new password" v-model="user.password"/>
                <my-input type="text" placeholder="confirm new password" v-model="user.password_confirmation"/>
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
        name: "ResetPassword",

        data: () => ({
            user: {
                email:"",
                password: "",
                password_confirmation: "",
            }
        }),

        created() {
            this.checkUserState();
        },

        computed: {
            ...mapGetters({
                invalidCredentials: 'auth/invalidCredentials',
                validationErrors: 'auth/error'
            })
        },

        methods: {

            ...mapActions({
                checkUserState: 'auth/setLoggedInstate',
                resetPassword: 'auth/resetPassword',
            }),

            sendForgotPassword() {
                const token = this.$router.params.token;
                this.resetPassword({...this.user, token})
            },
        },

    }
</script>

<style lang="scss">
    .reset-page{
        width: 360px;
        padding: 8% 0 0;
        margin: auto;
        display: flex;
    }

</style>
