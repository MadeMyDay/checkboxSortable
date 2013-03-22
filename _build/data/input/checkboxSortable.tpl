<div id="tv{$tv->id}-cb"></div>
<script type="text/javascript">
// <![CDATA[
{literal}
    var reader{/literal}{$tv->id}{literal} = new Ext.data.ArrayReader({}, [
        {name: 'id'},
        {name: 'name'},
        {name: 'value'},
        {name: 'label'},
        {name: 'checked'}
    ]);
    var store{/literal}{$tv->id}{literal} = new Ext.data.Store({
        autoDestroy: true,
        reader: reader{/literal}{$tv->id}{literal},
        data: [{/literal}
            {foreach from=$opts item=item key=k name=cbs}
                {literal}[{/literal}                
                'tv{$tv->id}-{$k}',
                'tv{$tv->id}[]',
                '{$item.value}',
                '{$item.text|escape:"javascript"}',
                '<center><input id="tv{$tv->id}-{$k}" type="checkbox" value="{$item.value}" name="tv{$tv->id}[]" {if $item.checked}checked{/if} /></center>'
                {literal}],{/literal}
            {/foreach}
        {literal}]
    });
    var grid{/literal}{$tv->id}{literal} = new Ext.grid.GridPanel({
        store: store{/literal}{$tv->id}{literal},
        colModel: new Ext.grid.ColumnModel({
            defaults: {
                width: 250,
                sortable: false
            },
            columns: [
                {id: 'label', header: '{/literal}{$checkboxsortable.cbs_name}{literal}', dataIndex: 'label', width: 420},
                {id: 'checked', header: '{/literal}{$checkboxsortable.cbs_active}{literal}', dataIndex: 'checked', width: 80}
            ]
        }),
        listeners: {
                    'click': {fn:MODx.fireResourceFormChange, scope:this}
        },
        viewConfig: {
            forceFit: true
        },
        width: 500,
        height: 450,
        autoHeight: true,
        frame: true,
        enableDragDrop: true,
        ddGroup: 'zusatzInhalteGroup',
        ddText: '{/literal}{$checkboxsortable.cbs_sort}{literal}',
        renderTo: {/literal}'tv{$tv->id}-cb'{literal}
    });
    var ddrow{/literal}{$tv->id}{literal} = new Ext.dd.DropTarget(
        grid{/literal}{$tv->id}{literal}.getView().mainBody, {
        ddGroup: 'zusatzInhalteGroup',
        notifyDrop: function(dd, e, data) {
            var sm = grid{/literal}{$tv->id}{literal}.getSelectionModel();
            var rows = sm.getSelections();
            var cindex = dd.getDragData(e).rowIndex;
            var store = grid{/literal}{$tv->id}{literal}.getStore();
			var checkbox_id = null;
			var cb = null;
            if (sm.hasSelection()) {
                for (i = 0; i < rows.length; i ++) {
                    checkbox_id = store.getById(rows[i].id).data.id;
					cb_checked = Ext.get(checkbox_id).dom.checked;
					store.remove(store.getById(rows[i].id));
                    store.insert(cindex,rows[i]);
					Ext.get(checkbox_id).dom.checked=cb_checked;
				
                }
                sm.selectRecords(rows);
            }
        }
    });
{/literal}
// ]]>
</script>