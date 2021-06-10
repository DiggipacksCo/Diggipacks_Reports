<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?= base_url('assets/if_box_download_48_10266.png');?>" type="image/x-icon">
<title><?=lang('lang_Inventory');?></title>
<?php $this->load->view('include/file'); ?>
<script type="text/javascript" src="<?=base_url();?>assets/js/angular/tickets.app.js"></script>
<style>
.chatperson{
  display: block;
  border-bottom: 1px solid #eee;
  width: 100%;
  display: flex;
  align-items: center;
  white-space: nowrap;
  overflow: hidden;
  margin-bottom: 15px;
  padding: 4px;
}
.chatperson:hover{
  text-decoration: none;
  border-bottom: 1px solid orange;
}
.namechat {
    display: inline-block;
    vertical-align: middle;
}
.chatperson .chatimg img{
  width: 40px;
  height: 40px;
  background-image: url('http://i.imgur.com/JqEuJ6t.png');
}
.chatperson .pname{
  font-size: 18px;
  padding-left: 5px;
}
.chatperson .lastmsg{
  font-size: 12px;
  padding-left: 5px;
  color: #ccc;
}
.col-md-2, .col-md-10{
    padding:0;
}
.panel{
    margin-bottom: 0px;
}
.chat-window{
    bottom:0;
    position:fixed;
    float:right;
    margin-left:10px;
}
.chat-window > div > .panel{
    border-radius: 5px 5px 0 0;
}
.icon_minim{
    padding:2px 10px;
}
.msg_container_base{
  background: #e5e5e5;
  margin: 0;
  padding: 0 10px 10px;
 height: 300px;
  overflow-y:auto;
}
.top-bar {
  background: #666;
  color: white;
  padding: 10px;
  position: relative;
  overflow: hidden;
}
.msg_receive{
    padding-left:0;
    margin-left:0;
}
.msg_sent{
    padding-bottom:20px !important;
    margin-right:0;
}
.messages {
  background: white;
  padding: 10px;
  border-radius: 2px;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  max-width:100%;
}
.messages > p {
    font-size: 13px;
    margin: 0 0 0.2rem 0;
  }
.messages > time {
    font-size: 11px;
    color: #ccc;
}
.msg_container {
    padding: 10px;
    overflow: hidden;
    display: flex;
}

.avatar {
    position: relative;
}
.base_receive > .avatar:after {
    content: "";
    position: absolute;
    top: 0;
    right: 0;
    width: 0;
    height: 0;
    border: 5px solid #FFF;
    border-left-color: rgba(0, 0, 0, 0);
    border-bottom-color: rgba(0, 0, 0, 0);
}

.base_sent {
  justify-content: flex-end;
  align-items: flex-end;
}
.base_sent > .avatar:after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 0;
    border: 5px solid white;
    border-right-color: transparent;
    border-top-color: transparent;
    box-shadow: 1px 1px 2px rgba(black, 0.2); // not quite perfect but close
}

.msg_sent > time{
    float: right;
}



.msg_container_base::-webkit-scrollbar-track
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar
{
    width: 12px;
    background-color: #F5F5F5;
}

.msg_container_base::-webkit-scrollbar-thumb
{
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
    background-color: #555;
}

.btn-group.dropup{
    position:fixed;
    left:0px;
    bottom:0;
}
</style>
</head>

<body ng-app="AppTickets" ng-controller="CTR_ticketlist_fulfil" >
<?php $this->load->view('include/main_navbar'); ?>

<!-- Page container -->
<div class="page-container" > 
  
  <!-- Page content -->
  <div class="page-content">
    <?php $this->load->view('include/main_sidebar'); ?>
    
    <!-- Main content -->
    <div class="content-wrapper" > 
      <!--style="background-color: black;"-->
      <?php $this->load->view('include/page_header'); ?>
      
      <!-- Content area -->
      <div class="content" > 
        <!--style="background-color: red;"-->
        
        <div class="row" >
          <div class="col-lg-12" > 
            
            <!-- Marketing campaigns -->
            <div class="panel panel-flat">
              <div class="panel-heading"dir="ltr">
                <h1> <strong><?=lang('lang_All_Ticket_List');?> (<?=$ticketData['ticket_id'];?>)</strong> 
                    <a href="<?php echo base_url();?>showTicketview" class="pull-right btn btn-danger" ><?=lang('lang_Back');?></a>
                    <a  class="pull-right btn btn-info" ng-click="getcheckpopshow(<?=$ticketData['id'];?>,<?=$oldtcount;?>,'interCron');" style="margin-inline-end: 5px;" ><?=lang('lang_Chat_History');?></a> 
                  <!-- <a id="pdf" ><i class="icon-file-pdf pull-right" style="font-size: 35px;color: red;"></i></a>--> 
                </h1>
              </div>
              
              <!-- Quick stats boxes -->
              <div class="table-responsive " >
                <div class="col-lg-12" style="padding-left: 20px;padding-right: 20px;"> 
                  
                  <!-- Today's revenue --> 
                  
                  <!-- <div class="panel-body" > -->
                  
                  <table class="table table-bordered table-hover" style="width: 100%;">
                    <!-- width="170px;" height="200px;" -->
                    <tbody >
                      <tr>
                        <td><div class="form-group" ><strong><?=lang('lang_Ticket_ID');?>:</strong></div></td>
                        <td><?=$ticketData['ticket_id'];?></td>
                      </tr>
                      <tr>
                        <td><div class="form-group" ><strong># <?=lang('lang_AWB');?>:</strong></div></td>
                        <td><?=$ticketData['awb_no'];?></td>
                      </tr>
                      <tr>
                        <td><div class="form-group" ><strong><?=lang('lang_Seller');?>:</strong></div></td>
                        <td><?=getallsellerdatabyID($ticketData['seller_id'],'name');?></td>
                      </tr>
                      <tr>
                        <td><div class="form-group" ><strong><?=lang('lang_Subjet');?>:</strong></div></td>
                        <td><?=$ticketData['subject'];?></td>
                      </tr>
                      <tr>
                        <td><div class="form-group" ><strong><?=lang('lang_Message');?>:</strong></div></td>
                        <td><?=$ticketData['message'];?></td>
                      </tr>
                      <tr>
                        <td><div class="form-group" ><strong><?=lang('lang_Status');?>:</strong></div></td>
                        <td>
                        <?php
						if($ticketData['status']=='pending')
                        echo'<span class="badge badge-danger">Pending</span>';
						if($ticketData['status']=='process')
						echo'<span class="badge badge-warning" >Process</span>';
						if($ticketData['status']=='complated')
						echo'<span class="badge badge-success">completed</span>';
						?>
                        </td>
                      </tr>
                      <tr>
                        <td><div class="form-group" ><strong><?=lang('lang_Created_Date');?>:</strong></div></td>
                        <td><?=$ticketData['entrydate'];?></td>
                      </tr>
                      
                      <!-- <td><button  class="btn btn-danger" ng-click="loadMore(1,1);" >Search</button></td>-->
                      
                    </tbody>
                  </table>
                  
                   
                  <br>
                  
                  <!-- </div> panel-body--> 
                  
                  <!-- /today's revenue --> 
                  
                </div>
              </div>
              
              <!-- /quick stats boxes --> 
            </div>
          </div>
        </div>
        
        <!-- /basic responsive table -->
        <?php $this->load->view('include/footer'); ?>
      </div>
      <!-- /content area --> 
      
    </div>
    <!-- /main content --> 
    
  </div>
  <!-- /page content --> 
  
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    
       <div class="container">
	<div class="row">
                 
                 
                 
                 
                 <div class="col-sm-5">
                  <div class="chatbody">
                  <div class="panel panel-primary">
                <div class="panel-heading top-bar">
                    <div class="col-md-12 col-xs-12">
                        <h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span>  <?=lang('lang_Chat');?> <a data-dismiss="modal" class="pull-right btn btn-danger" ><?=lang('lang_Close');?></a> </h3>
                        
                    </div>
                </div>
                <input type="hidden" ng-model="historyData.oldtcount" name="oldtcount" id="oldtcount" value="{{ShowhistoryData.oldtcount}}">
                <div class="panel-body msg_container_base" >
                 <div  ng-if='ShowhistoryData!=0' ng-repeat="data in ShowhistoryData">
                 <div class="row msg_container base_sent" id="scrrclass{{$index+1}}" ng-if="data.position=='right'">
                        <div class="col-md-10 col-xs-10">
                            <div class="messages msg_sent">
                               <span class="badge badge-success">{{data.user_name}}</span>&nbsp;&nbsp;&nbsp;&nbsp; <p>{{data.message}}</p>
                                <time datetime="2009-11-13T20:00">{{data.entrydate}}</time>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row msg_container base_receive" id="scrrclass{{$index+1}}" ng-if="data.position=='left'">
                       
                        <div class="col-md-7 col-xs-7">
                            <div class="messages msg_receive">
                               <span class="badge badge-success">{{data.seller_name}}</span>  <p>{{data.message}}</p>
                                <time datetime="2009-11-13T20:00">{{data.entrydate}}</time>
                            </div>
                        </div>
                    </div>
                    
                    
                    
                     
                    </div> 
                    
                 
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input  type="text" class="form-control input-sm chat_input" id="replymess" name="replymess" ng-model="historyData.replymess"  placeholder="Write your message here..." />
                        <span class="input-group-btn">
                        <button class="btn btn-primary btn-sm" id="btn-chat" ng-click="Getreplyadd();"><i class="fa fa-send fa-1x" aria-hidden="true"></i></button>
                        </span>
                    </div>
                </div>
    		</div>

                 </div>
             </div>

  </div>
</div>

</div></div>
<script>


$(document).ready(function() {
    var table = $('#example').DataTable({});
});  
</script>
<!-- /page container -->

</body>
</html>
