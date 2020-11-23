<template>
    <a href="/services/flights">
        <i class="fa fa-plane-departure"></i>
        <span class="badge badge-primary" v-if="total">{{ total }}</span>
    </a>
</template>

<script>
import axios from "axios";
export default {
  data() {
    return {
      total: 0,
    };
  },
  created () {
    window.Echo.channel("new-flight").listen("NewFlightEvent", (e) => {
      total = e.count;
      console.log(e);
    //   audio.play();
    });
  },
//   computed: {
//     audio () {
//       return new Audio("http://soundbible.com/mp3/sms-alert-4-daniel_simon.mp3");
//     }
//   }
};
</script>
