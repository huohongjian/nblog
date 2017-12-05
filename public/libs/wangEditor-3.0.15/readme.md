
修改内容：
1) .w-e-toolbar,.w-e-text-container,.w-e-menu-panel { padding: 0;  margin: 0;} 改为
   .w-e-toolbar,.w-e-text-container,.w-e-menu-panel {padding_: 0; margin_: 0;}

2) .w-e-toolbar *,.w-e-text-container *,.w-e-menu-panel * { padding: 0;  margin: 0;} 改为
   .w-e-toolbar *,.w-e-text-container *,.w-e-menu-panel * {padding_: 0; margin_: 0;}

3) $textContainerElem.css('border', '1px solid #ccc').css('border-top', 'none').css('height', '300px'); 改为
   $textContainerElem.css('border', '1px solid #ccc').css('border-top', 'none').css('height', '100%');