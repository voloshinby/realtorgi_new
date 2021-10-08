<template>
  <div>
    <button @click="createNotification">Click</button>
  </div>
</template>

<script>
import moment from 'moment'
export default {
  data() {
    return {
      label: 'Логин:',
      moment: moment,
    }
  },
  methods: {
    createNotification() {
      this.$notify({
        'group': 'user-notifications',
        'title': `<div class='title'>Изменения данных.</div> <div class='notification-date'>${moment((Date.parse(new Date()))).format('HH:mm')}</div>`,
        'text': 'Вы успешно сменили данные своего аккаунта.',
      })
    }
  }

}
</script>
