<?php

class BrandsController extends AppController {

    var $name = 'Brands';
    
    public function view($slug='/'){
    	$modelClass = $this->modelClass;
    	
    	parent::view($slug);
    	
    	$id = $this->viewdata[$modelClass]['id'];
    	$this->loadModel('Product');
    	$products = $this->Product->find('all',array(
    		'conditions' => array(
    			'brand_id' => $id,
    			'Product.published' => 1, 
    			'Product.deleted' => 0
    		),
    		'limit'=>'20',
    	));
    	$this->set('products',$products);
    }

    public function brands_admin() {

        if(empty($this->currentUser['id'])){
            $this->redirect('/users/login?referer='.Router::url('/brands/brands_admin'));
        }

        if ($this->is_admin($this->currentUser['id'])) {
            $brands = $this->Brand->find('all', array(
                'fields' => array('creator', 'name')
            ));
            $this->set('brands', $brands);
        } else {
            echo "You are not authorized to visit this page!";
            exit;
        }
    }
}
?>