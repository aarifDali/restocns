function search(nameKey, myArray){
    let foundResult=new Array();
    let counter = 0;
    for (let i=0; i < myArray.length; i++) {
        // if (myArray[i].item_name === nameKey) {
        //     return myArray[i];
        // }
        if (myArray[i].item_name.toLowerCase().includes(nameKey.toLowerCase()) || myArray[i].item_code.toLowerCase().includes(nameKey.toLowerCase()) || myArray[i].category_name.toLowerCase().includes(nameKey.toLowerCase()) || myArray[i].veg_item.includes(nameKey) || myArray[i].beverage_item.toUpperCase().includes(nameKey)) {
            foundResult.push(myArray[i]);
            counter++;
            if (nameKey && counter == 12) {
                break;
            }
        }
    }
    return foundResult.sort( function(a, b) {
      return parseInt(b.sold_for)-parseInt(a.sold_for);
    });
    //this is comment. it could be used if we want to sort this collection of object by item_name or anything else
    // return foundResult.sort( predicateBy("item_name") );
    
}
function getAlternativeNameById(menu_id,myArray){
    let name = '';
    for (let i=0; i < myArray.length; i++) {
        if (Number(myArray[i].item_id) === Number(menu_id)) {
            if(myArray[i].alternative_name){
                name = "("+myArray[i].alternative_name+")";
            }
        }
    }
    return name;
}
// Helper function to check if menu_name already contains alternative name
function shouldAppendAlternativeName(menu_name, alternative_name) {
    if (!alternative_name || alternative_name === '') {
        return false;
    }
    // Check if menu_name already ends with the alternative name in brackets
    let alt_pattern = "(" + alternative_name + ")";
    if (menu_name && menu_name.trim().endsWith(alt_pattern)) {
        return false; // Already contains it, don't append
    }
    // Check if menu_name already contains any pattern like "(...)" at the end
    // This handles cases where menu_name is already formatted from frontend
    let bracket_pattern = /\([^)]+\)\s*$/;
    if (menu_name && bracket_pattern.test(menu_name.trim())) {
        // If it already has brackets, check if it matches the alternative name
        let match = menu_name.trim().match(/\(([^)]+)\)\s*$/);
        if (match && match[1] === alternative_name) {
            return false; // Already contains the same alternative name
        }
    }
    return true; // Safe to append
}

function searchAddress(nameKey, myArray){
    let foundResult=new Array();
    let counter = 0;
    for (let i=0; i < myArray.length; i++) {
        // if (myArray[i].item_name === nameKey) {
        //     return myArray[i];
        // }
        if (myArray[i].customer_id == nameKey) {
            foundResult.push(myArray[i]);
            counter++;
            if (nameKey && counter == 12) {
                break;
            }
        }
    }
    return foundResult;
    
}

function search_by_menu_id(menu_id,myArray){
    let foundResult=new Array();
    for (let i=0; i < myArray.length; i++) {
        if (Number(myArray[i].item_id) ===  Number(menu_id)) {
            foundResult.push(myArray[i]);
        }
    }
    return foundResult.sort();
}
function search_by_menu_id_getting_parent_id(menu_id,myArray){
    let parent_id = '';
    for (let i=0; i < myArray.length; i++) {
        if (Number(myArray[i].item_id) === Number(menu_id)) {
            parent_id = myArray[i].parent_id;
        }
    }
    return parent_id;
}
function get_variations_search_by_menu_id(menu_id,myArray){
    let foundResult=new Array();
    for (let i=0; i < myArray.length; i++) {
        if (Number(myArray[i].parent_id) === menu_id) {
            foundResult.push(myArray[i]);
        }
    }
    return foundResult.sort();
}

function predicateBy(prop){
   return function(a,b){
      if( a[prop] > b[prop]){
          return 1;
      }else if( a[prop] < b[prop] ){
          return -1;
      }
      return 0;
   }
}