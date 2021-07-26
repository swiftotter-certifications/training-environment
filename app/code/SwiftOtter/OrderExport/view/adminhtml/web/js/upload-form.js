define(['jquery', 'Magento_Ui/js/modal/alert'], function($, alert) {
  class UploadForm {

    constructor (config, el) {
      this.config = config;
      this.parent = el;
      this.button = this.parent.querySelector('#submit-order-export');
      this.success = el.querySelector('#order-export-success');
      this.error = el.querySelector('#order-export-error');

      this.initialize();
    }

    initialize() {
      const button = this.button;
      button.addEventListener('click', this.send.bind(this));
      this.unsetButtonLoadingMessage();
    }

    handle(response) {
      if (!response.status || response.status === "error") {
        return this.handleError(response);
      }

      console.log(response);

      this.unsetButtonLoadingMessage();
      this.success.style.display = 'flex';
      this.error.style.display = 'none';
    }

    handleError(response) {
      this.unsetButtonLoadingMessage();

      console.log(response);

      if (response.status && response.status === "error" && response.result) {
        this.error.innerText = response.result;
      } else {
        this.error.innerText = JSON.stringify(response);
      }

      this.success.style.display = 'none';
      this.error.style.display = 'block';
    }

    setButtonLoadingMessage() {
      this.button.querySelector('span').innerText = this.config.sending_message;
      this.button.disabled = true;
    }

    unsetButtonLoadingMessage() {
      this.button.querySelector('span').innerText = this.config.original_message;
      this.button.disabled = false;
    }

    send() {
      this.setButtonLoadingMessage();

      let details = {
        method: 'POST',
        body: this.collect()
      };

      if (this.config.hasOwnProperty('token')) {
        details.headers = {
          'Authorization': 'Bearer ' + this.config.token,
          'Content-Type': 'application/json'
        }

        details.body = JSON.stringify({
          incomingHeaderData: this.collectAsObject()
        })
      }

      fetch(
        this.config.upload_url, details
      ).then(response => response.json())
        .catch(error => this.handleError(error))
        .then(response => this.handle(response));
    }

    collectAsObject() {
      return {
        'ship_date': this.parent.querySelector('#order-export-requested-date').value,
        'merchant_notes': this.parent.querySelector('#order-export-message').value
      };
    }

    collect() {
      const formData = new FormData();

      formData.append('form_key', this.config.form_key);
      formData.append('ship_date', this.parent.querySelector('#order-export-requested-date').value);
      formData.append('merchant_notes', this.parent.querySelector('#order-export-message').value);

      return formData;
    }
  }

  return function(config, el) {
    return new UploadForm(config, el);
  }
});