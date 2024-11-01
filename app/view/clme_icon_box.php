<?php
add_filter('http_request_args', 'clme_my_http_request_args', 100, 1);
function clme_my_http_request_args($r)
{
  $r['timeout'] = 30;
  return $r;
}
function clme_icon_box()
{
  $token = get_option('cloodo_token');
  $code_workchat = get_option('cloodo_code_workchat');
  if ($token && $token != null) {
    if (!isset($code_workchat) || $code_workchat == null) {
      $author_token = [
        'headers' => ['Authorization' => 'Bearer ' . $token],
      ];
      $ticket_agents = wp_safe_remote_get('https://erp-amz.cloodo.com/v4/widgets/list', $author_token);
      $check_ticke_agent = json_decode(wp_remote_retrieve_body($ticket_agents))->data;

      if (empty($check_ticke_agent)) {
        $data_widget = clme_check_ticketAgent($author_token);
      } else {
        $data_widget = current(json_decode(wp_remote_retrieve_body($ticket_agents))->data);
      }

      if (empty($data_widget)) {
        $data_widget = clme_check_dataWidget($ticket_agents);
        $id_data_widget = current(json_decode(wp_remote_retrieve_body($data_widget)));
      } else {
        $id_data_widget = $data_widget->id;
      }

      if (isset($data_widget)) {
        $cloodo_company = current(get_option('cloodo_company'))->x_token;
        $token_get_data_widget = [
          'headers' => [
            'Authorization' => 'Bearer ' . get_option('cloodo_token'),
            'X-Worksuite-Company-Token' => $cloodo_company,
          ],
          'body' => [
            'id' => $id_data_widget,
          ]
        ];

        $get_data_widget = wp_remote_post('https://erp-amz.cloodo.com/v4/widgets/detail', $token_get_data_widget);
        $get_data_widget = json_decode(wp_remote_retrieve_body($get_data_widget));
        $code_workchat = $get_data_widget->data_widget->code;
        update_option("cloodo_code_workchat", $code_workchat);
      }
    }
    if ($code_workchat) {
      echo '<iframe id="chatco_popup" src="' . esc_url('https://api.cloodo.com/v1/popup_chat/?integrity=' . $code_workchat) . '" width="0" height="0"></iframe><script src="https://images-products.s3.us-east-1.amazonaws.com/popup-chat/chatcov2.js"></script>';
    }
  }
}

function clme_check_ticketAgent($author_token)
{
  $groups = wp_remote_get('https://erp.cloodo.com/api/v2/ticket-groups', $author_token);
  $groups = json_decode(wp_remote_retrieve_body($groups));
  $group_id = current($groups->data)->id;

  $users = wp_safe_remote_get('https://erp.cloodo.com/api/v1/user', $author_token);
  $users = json_decode(wp_remote_retrieve_body($users));
  $user_id = current($users->data)->id;

  $token_ticket_agent = [
    'headers' => ['Authorization' => 'Bearer ' . get_option('cloodo_token')],
    'body' => [
      'group_id' => $group_id,
      'status' => 'enabled',
      'user_ids[]' => $user_id
    ]
  ];
  $ticket_agents = wp_remote_post('https://erp.cloodo.com/api/v2/ticket-agents', $token_ticket_agent);
  $ticket_agents = current(json_decode(wp_remote_retrieve_body($ticket_agents))->data);

  return $ticket_agents;
}

function clme_check_dataWidget($ticket_agents)
{
  $company_name = get_option('blogname');
  $value_agent = $ticket_agents->user->name . '[' . $ticket_agents->user->email . ']';

  $dataf = '{
        "channelNameAsync":"' . $company_name . '",
        "agent":{
          "value":' . $ticket_agents->id . ',
          "label":"' . $value_agent . '"
        },
        "fbNameAsync":"",
        "telegramAsync":"",
        "skypeAsync":"",
        "zaloAsync":"",
        "phoneWabaAsync":"",
        "slackAsync":""
      }';

  $token_create_data_widget = [
    'headers' => ['Authorization' => 'Bearer ' . get_option('cloodo_token')],
    'body' => [
      'type' => 'agent',
      'name' => $company_name,
      'dataf' => $dataf,
      'status' => 1,
      'act' => 'save',
    ]
  ];
  $data_widget = wp_remote_post('https://erp.cloodo.com/api/v1/list-company-map', $token_create_data_widget);

  return $data_widget;
}