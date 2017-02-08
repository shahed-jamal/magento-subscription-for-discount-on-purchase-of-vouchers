<?php
/**
 * Shahed Jamal
 * @category    Magento Module
 * @package     Discount
 * @copyright   Copyright (c) 2017 Shahed Jamal.
 * @purpose     main observer
 * @author      Shahed Jamal
 **/
class Shahed_Discount_Model_Observer
{

    public function __construct()
    {

    }

    /**
     * Observer function to add data in shahed_discount_history table on order state changed to complete
     * @param $observer
     */
    public function saveOrderDetailsInHistory($observer)
    {
        $event = $observer->getEvent();
        $state = $event->getState();
        $order=$event->getOrder();

        $items = $order->getAllVisibleItems();
        $productIds = array();
        foreach($items as $i):
            $productIds[] = $i->getProductId();
        endforeach;

        //Collect data to add in shahed_discount_history table
        $orderId = $order->getId();
        $customerId=$order->getCustomerId();
        $createdAt=$order->getCreatedAt();
//        $grandTotal=$order->getGrandTotal();

        // Get details of enabled discount set in backend
        $modelDiscounts = Mage::getModel('shahed_discount/discount')->getCollection()
            ->addFieldToFilter('is_enabled',1)
            ->getData();
        foreach ($modelDiscounts as $modelDiscount) {
            $setProductId[] = $modelDiscount['product_id'];
            $minAmount[] = $modelDiscount['min_price'];
            $disMonth[] = $modelDiscount['discount_month'];
            $disPercentage[] = $modelDiscount['discount_percentage'];
        }
        // Find the discount min amount to set discount & get no of month and percentage to set discount
//        $closestAmount = $this->closest($minAmount,$grandTotal);

        // Check voucher product is present in order or not -- If present the add/update history table else return
        $resultWithIndex = array_intersect($setProductId, $productIds);
        if ($resultWithIndex){
            foreach ($resultWithIndex as $key => $pId) {
                    $closestAmount = $minAmount[$key];
                    $discountMonth = $disMonth[$key];
                    $discountPercentage = $disPercentage[$key];
            }
        } else {
            return;
        }

//        foreach ($minAmount as $key => $amount) {
//            if($amount == $closestAmount) {
//                $discountMonth = $disMonth[$key];
//                $discountPercentage = $disPercentage[$key];
//            }
//        }

        // Get customer old discount history to update date
        $customerHistory = Mage::getModel('shahed_discount/history')->getCollection()
            ->addFieldToFilter('customer_id',$customerId)
            ->getData();
        foreach ($customerHistory as $key => $history) {
            $endDateNow = strtotime(date('Y-m-d', strtotime("+$discountMonth months", strtotime($createdAt))));
            $endDatePrev=strtotime($history['end_date']);
            $discountPercentagePrev=$history['dis_percentage'];

            // checking if same amount dis
            if ($history['min_price'] == $closestAmount) {
                $operationMode = 'update';
                if($endDateNow <= $endDatePrev)
                {
                    $operationMode = 'discard';
                }
                if($discountPercentage < $discountPercentagePrev)
                {
                    $operationMode = 'discard';
                }
            }
        }

        // Insert or update data in shahed_discount_history table
        if ($state=='complete' && $closestAmount && $discountMonth && $discountPercentage && $orderId && $customerId) { //need to change status from cancel to complete
            $endDate = date('Y-m-d', strtotime("+$discountMonth months", strtotime($createdAt)));
            $data = array('customer_id' => $customerId, 'order_id' => $orderId, 'start_date' => $createdAt, 'end_date' =>$endDate , 'min_price' => $closestAmount, 'dis_percentage' => $discountPercentage);
            // If minimum amount is already present for particular customer then update the date
            if ($operationMode == 'discard') {
                // do nothing
            }elseif ($operationMode == 'update') {
                try {
                    $model = Mage::getModel('shahed_discount/history');
                    $model->load($closestAmount, 'min_price')->addData($data);
                    $model->save();
                    $insertId = $model->getId();
                    echo "Data updated successfully. Update ID: ".$insertId;
                } catch (Exception $e){
                    echo $e->getMessage();
                }
            } else { // If minimum amount is not present then insert in table
                try {
                    $model = Mage::getModel('shahed_discount/history')->addData($data);
                    $model->save();
                    $insertId = $model->getId();
                    echo "Data successfully inserted. Insert ID: " . $insertId;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            }
        }
    }

    /**
     * Change product price in list page
     * @param $observer
     * @return $this
     */
    public function getFinalProductPriceList($observer)
    {
        /* Check if the customer is logged in, if so, then we proceed */
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $event = $observer->getEvent();
            $products = $event->getCollection();
            $customerId = Mage::getSingleton('customer/session')->getCustomerId();
            $discount = $this->getCustomerDiscount($customerId);

            foreach ($products as $product) {

                $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
                $attributeSetModel->load($product->getAttributeSetId());
                $attributeSetName = $attributeSetModel->getAttributeSetName();
                $isSpecialPrice = $this->getProductFinalPrice($product);
                // check if product special price is present
                if ($isSpecialPrice && $attributeSetName != 'Voucher'){
                    if ($discount) {
                        $product->original_price = $product->getPrice();
                        $final_price = $product->original_price * (1 - $discount);
                        $product->setFinalPrice($final_price);
                    }
                }
            }
            return $this;
        }
    }


    /**
     * Change product price in product view page
     * @param $observer
     * @return $this
     */
    public function getFinalProductPrice($observer)
    {
        /* Check if the customer is logged in, if so, then we proceed */
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $event = $observer->getEvent();
            $product = $event->getProduct();

            $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
            $attributeSetModel->load($product->getAttributeSetId());
            $attributeSetName = $attributeSetModel->getAttributeSetName();
            $isSpecialPrice = $this->getProductFinalPrice($product);
            // check if product special price is present and product type is not voucher
            if ($isSpecialPrice && $attributeSetName != 'Voucher'){
                $customerId = Mage::getSingleton('customer/session')->getCustomerId();
                // Get customer old discount history to update date
                $discount = $this->getCustomerDiscount($customerId);
                if ($discount) {
                    $product->original_price = $product->getPrice();
                    $final_price = $product->original_price * (1 - $discount);
                    $product->setFinalPrice($final_price);
                }
            }
            return $this;
        }
    }



    /**
     * Get closest minimum amount from list of discount amounts
     * @param $array
     * @param $number
     * @return mixed|null
     */
    public function closest($array, $number) {
        rsort($array);
        if ($number >= end($array)) {
            foreach ($array as $a) {
                if ($a <= $number) return $a;
            }
            return end($array);
        } else {
            return null;
        }
    }

    /**
     *Check if product have special price
     * @param $product
     * @return bool
     */
    public function getProductFinalPrice($product) {
        $finalPrice = $product->getFinalPrice();
        $normalPrice = $product->getPrice();
        if ($finalPrice < $normalPrice) {
           return false;
        } else {
            return true;
        }
    }


    /**
     * Calculate discount for customer
     * @param $customerId
     * @return bool|float
     */
    protected function getCustomerDiscount($customerId)
    {
        // Get customer old discount history
        $customerHistory = Mage::getModel('shahed_discount/history')->getCollection()
            ->addFieldToFilter('customer_id', $customerId)
            ->getData();
        foreach ($customerHistory as $key => $history) {
            $currentDate = strtotime(date("Y-m-d"));
            $endDate = strtotime($history['end_date']);

            if ($currentDate <= $endDate) {
                $disPercentage[] = $history['dis_percentage'];
            }
        }
        rsort($disPercentage);
        if ($disPercentage) {
            $discountPercentage = $disPercentage[0];
            $discount = $discountPercentage / 100;
            return $discount;
        } else {
            return false;
        }
    }


    /**
     * Restrict users to add multiple vouchers at once
     * @param $observer
     */
    public function restrictCart($observer) {
        $error_message = false;
            $item = $observer->getEvent()->getQuoteItem();
            $attributeSetModel = Mage::getModel("eav/entity_attribute_set");
            $attributeSetModel->load($item->getProduct()->getAttributeSetId());
            $attributeSetName = $attributeSetModel->getAttributeSetName();
            if ($attributeSetName == "Voucher") {
                foreach ($item->getQuote()->getItemsCollection() as $_item) {
                    $allAttributeSetModel = Mage::getModel("eav/entity_attribute_set");
                    $allAttributeSetModel->load($_item->getProduct()->getAttributeSetId());
                    $allAttributeSetName = $allAttributeSetModel->getAttributeSetName();

                    if ($_item->getId() != $item->getId())
                    {
                        if ($allAttributeSetName == 'Voucher')
                        $_item->isDeleted(true);
                        $error_message = true;
                    }
                }
            }
        if($error_message)
            Mage::getSingleton('checkout/session')->addError('* Voucher products cannot be more than one in cart at once.');
    }

    /**
     * Disable guest checkout for vouchers type of products
     * @param $observer
     */
    public function restrictGuestCheckout($observer) {
        $quote = $observer->getEvent()->getQuote();
        $result = $observer->getEvent()->getResult();
        foreach ($quote->getAllItems() as $item){
            $allAttributeSetModel = Mage::getModel("eav/entity_attribute_set");
            $allAttributeSetModel->load($item->getProduct()->getAttributeSetId());
            $allAttributeSetName = $allAttributeSetModel->getAttributeSetName();
            if ($allAttributeSetName == 'Voucher'){
                $result->setIsAllowed(false);
                break;
            }
        }
    }
}