(function (window, angular, wx) {

  angular.module('weshares')
    .constant('wx', wx)
    .controller('WesharesEditCtrl', WesharesEditCtrl);


  function WesharesEditCtrl($scope, $rootScope, $log, $http, wx, Utils) {
    var vm = this;
    $document.ready(function(){
      $rootScope.loadingPage = false;
    });
    vm.chooseAndUploadImage = chooseAndUploadImage;
    vm.uploadImage = uploadImage;
    vm.deleteImage = deleteImage;

    vm.toggleProduct = toggleProduct;
    vm.toggleAddress = toggleAddress;

    vm.nextStep = nextStep;
    vm.saveWeshare = saveWeshare;

    vm.validateTitle = validateTitle;
    vm.validateProductName = validateProductName;
    vm.validateProductPrice = validateProductPrice;
    vm.validateAddress = validateAddress;
    vm.saveCacheData = saveCacheData;
    vm.validateShipFee = validateShipFee;
    vm.dataCacheKey = 'cache_share_data';
    vm.pageLoaded = pageLoaded;
    function pageLoaded(){
      $rootScope.loadingPage = false;
    }
    activate();
    $scope.$watchCollection('vm.weshare', vm.saveCacheData);
    function activate() {
      vm.showShippmentInfo = false;
      var weshareId = angular.element(document.getElementById('weshareEditView')).attr('data-id');
      var sharerShipType = angular.element(document.getElementById('weshareEditView')).attr('data-ship-type');
      vm.sharerShipType = sharerShipType;
      vm.self_ziti_data = {status: 1, ship_fee: 0, tag: 'self_ziti'};
      vm.kuai_di_data = {status: -1, ship_fee: '', tag: 'kuai_di'};
      vm.pys_ziti_data = {status: -1, ship_fee: 0, tag: 'pys_ziti'};
      vm.kuaidi_show_ship_fee = '';
      //add
      vm.weshare = {
        title: '',
        description: '',
        images: [],
        products: [
          {name: '', store: ''}
        ],
        send_info: '',
        addresses: [
          {address: ''}
        ]
      };
      var $cacheData = PYS.storage.load(vm.dataCacheKey);
      if ($cacheData) {
        vm.weshare = $cacheData;
        setDefaultData();
      }
      if (weshareId) {
        //update
        $http.get('/weshares/get_share_info/' + weshareId).success(function (data) {
          $log.log(data);
          vm.weshare = data;
          setDefaultData();
          vm.self_ziti_data = data['ship_type']['self_ziti'] || vm.self_ziti_data;
          vm.kuai_di_data = data['ship_type']['kuai_di'] || vm.kuai_di_data;
          vm.pys_ziti_data = data['ship_type']['pys_ziti'] || vm.pys_ziti_data;
          vm.kuaidi_show_ship_fee = vm.kuai_di_data.ship_fee/100;
        }).error(function(data){

        });
      }
      vm.messages = [];
      function setDefaultData(){
        if(!vm.weshare.addresses||vm.weshare.addresses.length==0){
          vm.weshare.addresses = [{address: ''}];
        }
        if(!vm.weshare.send_info){
          vm.weshare.send_info = '';
        }
      }
    }
			

    function chooseAndUploadImage() {
      wx.chooseImage({
        success: function (res) {
          //_.each(res.localIds, vm.uploadImage);
          //alert(res.localIds);
          //for(var local_id in res.localIds){
          //  vm.uploadImage(local_id);
          //}
          vm.uploadImage(res.localIds);
        },
        fail: function (res) {
          vm.messages.push({name: 'choose image failed', detail: res});
        }
      });
    }

    function saveCacheData() {
      PYS.storage.save(vm.dataCacheKey, vm.weshare, 1);
    }

    function uploadImage(localIds) {
      var i = 0, len = localIds.length;

      function upload() {
        wx.uploadImage({
          localId: localIds[i],
          isShowProgressTips: 1,
          success: function (res) {
            i++;
            $http.get('/downloads/download_wx_img?media_id=' + res.serverId).success(function (data, status, headers, config) {
              vm.messages.push({name: 'download image success', detail: data});
              var imageUrl = data['download_url'];
              if (!imageUrl || imageUrl == 'false') {
                return;
              }
              vm.weshare.images.push(imageUrl);
            }).error(function (data, status, headers, config) {
              vm.messages.push({name: 'download image failed', detail: data});
            });
            if (i < len) {
              upload();
            }
          },
          fail: function (res) {
            vm.messages.push({name: 'upload image failed', detail: res});
          }
        });
      }

      upload();
    }

    function deleteImage(image) {
      vm.weshare.images = _.without(vm.weshare.images, image);
    }

    function toggleProduct(product, isLast) {
      if (isLast) {
        vm.weshare.products.push({name: '', store: ''});
      }
      else {
        vm.weshare.products = _.without(vm.weshare.products, product);
      }
    }

    function toggleAddress(address, isLast) {
      if (isLast) {
        vm.weshare.addresses.push({address: ''});
      }
      else {
        vm.weshare.addresses = _.without(vm.weshare.addresses, address);
      }
    }

    function nextStep() {
      var titleHasError = vm.validateTitle();
      var productHasError = false;
      _.each(vm.weshare.products, function (product) {
        var nameHasError = vm.validateProductName(product);
        var priceHasError = vm.validateProductPrice(product);
        productHasError = productHasError || nameHasError || priceHasError;
      });
      if (titleHasError || productHasError) {
        return;
      }

      vm.showShippmentInfo = true;
    }

    function saveWeshare() {
      if (vm.isInProcess) {
        return;
      }
      vm.isInProcess = true;
      vm.weshare.addresses = _.filter(vm.weshare.addresses, function (address) {
        return !_.isEmpty(address.address);
      });
      if(vm.validateAddress()){
        vm.weshare.addresses= [
          {address: ''}
        ];
        return false;
      }
      vm.kuai_di_data.ship_fee = vm.kuai_di_data.ship_fee || 0;
      if(vm.validateShipFee(vm.kuai_di_data.ship_fee)){
        return false;
      }
      vm.kuai_di_data.ship_fee = vm.kuai_di_data.ship_fee;
      vm.weshare.ship_type = [vm.self_ziti_data,vm.kuai_di_data,vm.pys_ziti_data];
      $log.log('submitted').log(vm.weshare);
      $http.post('/weshares/save', vm.weshare).success(function (data, status, headers, config) {
        if (data.success) {
          $log.log('post succeeded, data: ').log(data);
          PYS.storage.clear();
          window.location.href = '/weshares/view/' + data['id'];
        }else {
          var uid = data['uid'];
          window.location.href = '/weshares/user_share_info/'+uid;
          $log.log("failed with status: " + status + ", data: ").log(data);
        }
      }).error(function (data, status, headers, config) {
        window.location.href = '/weshares/add';
        $log.log("failed with status :" + status + ", data: ").log(data).log(', and config').log(config);
      });
    }

    function validateShipFee(){
      if(Utils.isNumber(vm.kuaidi_show_ship_fee)){
        vm.kuai_di_data.ship_fee = vm.kuaidi_show_ship_fee*100;
      }
      if(!Utils.isNumber(vm.kuai_di_data.ship_fee)&&vm.kuai_di_data.status==1){
        vm.shipFeeHasError = true;
      }else{
        vm.shipFeeHasError = false;
      }
      return vm.shipFeeHasError;
    }

    function validateTitle() {
      vm.weshareTitleHasError = _.isEmpty(vm.weshare.title) || vm.weshare.title.length > 50;
      return vm.weshareTitleHasError;
    }

    function validateProductName(product) {
      product.nameHasError = _.isEmpty(product.name) || product.name.length > 20;
      return product.nameHasError;
    }

    function validateProductPrice(product) {
      product.priceHasError = !product.price || !Utils.isNumber(product.price);
      return product.priceHasError;
    }

    function validateAddress(){
      vm.addressError = vm.self_ziti_data.status==1&& _.isEmpty(vm.weshare.addresses)
      return vm.addressError;
    }
  }
})(window, window.angular, window.wx);