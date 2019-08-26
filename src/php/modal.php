<div class="modal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Забронировать</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="book-form">
        <div class="modal-body">
          <div class="form-group">
            <fieldset>
              <div id="your-name">
                <div class="row col-12">
                  <div class="col-md-6 col-12">
                    <label class="control-label" for="dateFirst">Ваше имя в сети</label>
                    <input type="text" class="input-sm form-control" id="cl_name" name="cl_name" />
                  </div>
                </div>
              </div>
              <div class="input-daterange input-group">
                <div class="row col-12">
                  <div class="col-md-6 col-12">
                    <label class="control-label" for="dateFirst">Дата заезда</label>
                    <input type="text" class="input-sm form-control" id="dateFirst" name="start" />
                  </div>
                  <div class="col-md-6 col-12">
                    <label class="control-label" for="dateSecond">Дата отъезда</label>
                    <input type="text" class="input-sm form-control" id="dateSecond" name="end" />
                  </div>
                </div>
              </div>
            </fieldset>
          </div>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary" value="Принять" />
          <div id="room-check">
            <span>Проверяем...</span>
            <img class="rot" src="src/image/findrat.png">
          </div>
          <div class="alert alert-dismissible alert-success">
            <strong>Комната свободна!</strong>
            <a href="#" class="alert-link">Можете залетать</a>.
          </div>
          <div class="alert alert-dismissible alert-info">
            <strong>Не, комната занята</strong> <a href="#" class="alert-link">выбирай другую дату, либо комнату</a>
          </div>
          <div class="alert alert-dismissible alert-danger">
            <strong>Так не пойдет</strong> <a href="#" class="alert-link">вводи что просят</a>
          </div>
        </div>
    </div>
    </form>
  </div>
</div>