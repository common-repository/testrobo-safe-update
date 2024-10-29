<h2>TestRobo Safe Update - #1 Easy Sanity Testing Tool Tailored For WordPress </h2>
<div> 
  <h4>Plugin Settings</h4>
  <form method="post" action="options.php">
  <?php settings_fields( 'trsu_options_group' ) ?>
  <table class="trsu-form-table">
  <tr valign="top">
  <th class="table-labels" scope="row"><label for="trsu_is_enabled">Enable</label></th>
  <td class="table-values">
    <input id="trsu_is_enabled" name="trsu_is_enabled" class="cb4 tgl tgl-light" <?php checked('on', get_option('trsu_is_enabled'), true); ?> type="checkbox"/>
    <label class="tgl-btn" for="trsu_is_enabled"> </label>
    </td>
  </tr>
  <tr valign="top">
  <th class="table-labels" scope="row"><label for="trsu_api_key">API Key</label></th>
  <td class="table-values">
    <input type="text" id="trsu_api_key" name="trsu_api_key" value="<?php echo htmlspecialchars(get_option('trsu_api_key')) ?>"  />
      <em>Get a FREE api key from www.testrobo.io and place it here</em>
  </td>
  </tr>
  <tr valign="top">
    <th class="table-labels" scope="row"><label for="trsu_suite_id">Suite ID</label></th>
    <td class="table-values">
      <input type="text" id="trsu_suite_id" name="trsu_suite_id" value="<?php echo htmlspecialchars(get_option('trsu_suite_id')) ?>"  />
      <em>The suite from which tests will trigger on detecting plugin/theme updates.</em>
    </td>
  </tr>
  <tr valign="top">
    <th class="table-labels" scope="row"><label for="trsu_suite_id"></label></th>
    <td class="table-values">
    </td>
  </tr>
  </table>
  <?php submit_button() ?>
  </form>
  <hr/>
  <div id="trsu-test">
  <h4>Test connection</h4>
  <button class="test-button"> Check Now </button>
  <div class="test-result">
    <p></p>  
  </div>
  </div>
  
  <hr/>
  <p class="steps">
  <strong> Get started in three easy steps: </strong>
  <ul class="steps-list">
    <li> Create your FREE account on www.testrobo.io </li>
    <li> Create your first test using our innovative test recording experience. Save it in a suite. </li>
    <li> Enter your API key and the suite id in your plugin settings (this page) </li>
  </ul>
  </p>
</div>