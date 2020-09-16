<?php
/**
 * Script called (Ajax) on scroll or click
 * loads more content with Lazy Loader
 */
$html = '';
if(!isset($lz_offset)) $lz_offset = 1;
if(!isset($lz_limit)) $lz_limit = 30;
if(isset($_POST['ajax']) && $_POST['ajax'] == 1){
    
    require_once('../../../common/lib.php');
    require_once('../../../common/define.php');

    if(isset($_POST['offset']) && is_numeric($_POST['offset'])
    && isset($_POST['limit']) && is_numeric($_POST['limit'])){
        $lz_offset = $_POST['offset'];
        $lz_limit =	$_POST['limit'];
    }
}
    
if(isset($db) && $db !== false){
    
    //$my_page_alias = $sys_pages['offers']['alias'];
    $my_page_alias ='offers';

    $query_offer  = 'SELECT * FROM pm_offer WHERE lang = '.LANG_ID.' AND checked = 1';
     if($id_destination>0){
       $query_offer .= ' AND id_destination = '.$id_destination;
     }
    $query_offer .= ' ORDER BY id LIMIT '.($lz_offset-1)*$lz_limit.', '.$lz_limit;
    $result_offer = $db->query($query_offer);

    $offer_id = 0;

    $result_offer_file = $db->prepare('SELECT * FROM pm_offer_file WHERE id_item = :offer_id AND checked = 1 AND lang = '.LANG_ID.' AND type = \'image\' AND file != \'\' ORDER BY rank LIMIT 1');
    $result_offer_file->bindParam(':offer_id', $offer_id);
    
    
if($result_offer !== false && $db->last_row_count() > 0){
    foreach($result_offer as $i => $row){
                                
        $offer_id    = $row['id'];
        $offer_name  = $row['name'];
        $offer_alias = $row['alias'];
        $min_price = $row['offer_price'];
        
        $offer_alias = DOCBASE.$my_page_alias.'/'.text_format($offer_alias);
      
      
                       
          $html .= '
          <article class="col-sm-6 col-md-3 isotopeItem offer" itemscope itemtype="http://schema.org/Place">
            <div class="isotopeInner">
                <a itemprop="url" href="'.$offer_alias.'">';
                    if($result_offer_file->execute() !== false && $db->last_row_count() > 0){
                        $row = $result_offer_file->fetch(PDO::FETCH_ASSOC);
                        
                        $file_id = $row['id'];
                        $filename = $row['file'];
                        $label = $row['label'];
                        
                        $realpath = SYSBASE.'medias/offer/medium/'.$file_id.'/'.$filename;
                        $thumbpath = DOCBASE.'medias/offer/medium/'.$file_id.'/'.$filename;
                        $zoompath = DOCBASE.'medias/offer/big/'.$file_id.'/'.$filename;
                        
                        if(is_file($realpath)){
                            $html .= '
                            <figure class="more-link">
                                <img alt="'.$label.'" src="'.$thumbpath.'" class="img-responsive">
                                <span class="more-action">
                                    <span class="more-icon"><i class="fa fa-link"></i></span>
                                </span>
                            </figure>';
                        }else{
                            $html .= '
                            <figure class="more-link">
                                <img alt="'.$label.'" src="'.getFromTemplate("images/no-image.png").'" class="img-responsive">
                                <span class="more-action">
                                    <span class="more-icon"><i class="fa fa-link"></i></span>
                                </span>
                            </figure>';
                            }
                      }else{
                            $html .= '
                            <figure class="more-link">
                                <img alt="'.$label.'" src="'.getFromTemplate("images/no-image.png").'" class="img-responsive">
                                <span class="more-action">
                                    <span class="more-icon"><i class="fa fa-link"></i></span>
                                </span>
                            </figure>';
                       }
                    $html .= '
                    <div class="isotopeContent">
                        <h3 itemprop="name">'.$offer_name.'</h3>';
                        $html .= '
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="price text-primary">
                                    '.$texts['OFFER_PRICE'].'
                                    <span itemprop="priceRange">
                                        '.formatPrice($min_price*CURRENCY_RATE).'
                                    </span>
                                </div>
                                <div class="text-muted"></div>
                            </div>
                            <div class="col-xs-6">
                                <span class="btn btn-primary mt5 pull-right">'.$texts['MORE_DETAILS'].'</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </article>';
       
    }
    
}else{
    $html .= '<div class="not_found">Offer Not Found !</div>';
}
    if(isset($_POST['ajax']) && $_POST['ajax'] == 1)
        echo json_encode(array('html' => $html));
    else
        echo $html;
}
