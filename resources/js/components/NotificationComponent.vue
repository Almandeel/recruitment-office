<template>
    <div>
        <li class="navbar-link">
            <a href="/mail" class="nav-link">
                <i class="fa fa-bell"></i>
                <span class="badge badge-primary">{{ total }}</span>
            </a>
        </li>
    </div>
</template>

<script>
    import axios from "axios";
    export default {
        data() {
            return {
                total: 0
            }
        },
        mounted() {
            window.Echo.channel("new-message").listen("NewMessageEvent", e => {
                let audio = new Audio('http://soundbible.com/mp3/sms-alert-4-daniel_simon.mp3');
                audio.play();
                this.fetch()
            })
        },
        methods: {
            fetch() {
                axios.get("/notifications").then(({
                    data: {
                        total,
                    }
                }) => {
                    this.total = total;
                });
            },
        },
        beforeMount() {
            this.fetch()
        }
    }

</script>
