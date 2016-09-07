<div id='toolbar-box' style='width:200px;height:auto;'>
	<div class='t'>
		<div class='t'>
			<div class='t'></div>
		</div>
	</div>
	<div class='m' >
		<div class='toolbar2' id='toolbar'>
			<div>
			<span class='head2'>Разделы сайта</span>
<!-- aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa -->
  <div style='width:180px;overflow:auto;height:auto;'>

<form name="form1" action=""> 
<div > 
    <table cellspacing="2" cellpadding="0" style="margin: 0; background-color: #c0c0c0" width="100%"> 
    <tr> 
    <td width=1> 
        &nbsp;
    </td> 
    <td align=left width=1> 
        <table cellspacing="1" align=left width=1> 
        <tr> 
        <!--	<td class="coolButton" tabIndex="0" align=center onMouseOver="showtip(this,event,'Управление списком разделов');" action="alert()"><img src="templates/{$theme_name}/images/nav_ritem.gif"></td> --> 
        <!--	<td class="coolButton" tabIndex="0" align=center onMouseOver="showtip(this,event,'Создать новый раздел');" action=""><select><option>rus</option><option>ukr</option><option>eng</option></select></td> --> 
        <!--	<td class="coolButton" tabIndex="0" align=center onMouseOver="showtip(this,event,'Статистика');"><a href="statshow.php" target="content"><img border="0" src="templates/{$theme_name}/images/ico16_stat.gif"></a></td>
            <td class="coolButton" tabIndex="0" align=center onMouseOver="showtip(this,event,'TopImages');" action="parent.content.document.location='lc.content.php?sys=9';"><img src="templates/{$theme_name}/images/image.gif"></td>	--> 
        <td align=center onMouseOver="showtip(this,event,'Посмотреть сайт');"><a href="../" target=_blank><img src="templates/{$theme_name}/images/ico16_explorer.gif" alt="" border="0"></a></td> 
        </tr> 
        </table> 
    </td> 
    <td width=1> 
        <select name=lang class='select' onChange="change_lang()"> 
            <!-- <select name=lang class='select' onChange="parent.top.document.temp.lang.value=document.form1.lang.options[document.form1.lang.selectedIndex].value; document.location='lc.tree.php?lang='+document.form1.lang.options[document.form1.lang.selectedIndex].value"> --> 
 

            <option value=3 class="option" >ru</option> 
        </select> 
    </td> 
    <td width=100></td> 
    </tr> 
    </table> 
</div> 
 
 
<span style="white-space: nowrap;"> 
<a href="#" onClick="openFolder(0);"><img src=templates/{$theme_name}/images/nav_rfolder.gif width=20 height=20 border=0 alt="Корневой каталог" align="absmiddle" alt=""></a> 
</span> 
<br> 
 
<script language="JavaScript"> 
var NodesIndexes=Array();
var NodesTags=Array();
var NodeNames=Array();
var NodesStatus=Array();
var TreeTable=Array();
var haveNoChilds=Array();
var oldFolder=-1;
function InitTree() {
    tmpTags = document.getElementsByTagName("span");
    for (i=0; i<tmpTags.length; i++)
        if (tmpTags[i].id.substring(0,5) == "nname") {
            NodeID=tmpTags[i].id.substring(6);
            NodeNames[NodeID]=tmpTags[i];
        }
	NodeNames[160].style.color = "red";
    for (i=0; i<tmpTags.length; i++)
        tmpTags = document.getElementsByTagName("DIV");
    
    
 
    TreeTable[160]=0;
    TreeTable[184]=0;
    TreeTable[185]=0;
    TreeTable[232]=160;
    TreeTable[233]=232;
    TreeTable[234]=232;
    TreeTable[235]=232;
    TreeTable[236]=160;
    TreeTable[237]=160;
    TreeTable[238]=160;
    TreeTable[239]=160;
    TreeTable[240]=160;
    TreeTable[242]=160;
    TreeTable[243]=160;
    TreeTable[244]=160;
    TreeTable[245]=185;
    TreeTable[246]=245;
    TreeTable[247]=245;
    TreeTable[248]=245;
    TreeTable[249]=185;
    TreeTable[250]=185;
    TreeTable[251]=185;
    TreeTable[252]=185;
    TreeTable[253]=185;
    TreeTable[254]=185;
    TreeTable[255]=185;
    TreeTable[256]=185;
    TreeTable[257]=184;
    TreeTable[258]=184;
    TreeTable[259]=184;
    TreeTable[260]=257;
    TreeTable[261]=251;
    TreeTable[262]=251;
    TreeTable[263]=251;
    TreeTable[264]=251;
    TreeTable[265]=251;
    TreeTable[267]=251;
    TreeTable[268]=251;
    TreeTable[269]=251;
    TreeTable[270]=251;
    TreeTable[271]=251;
    TreeTable[272]=251;
    TreeTable[273]=251;
    TreeTable[274]=251;
    TreeTable[275]=249;
    TreeTable[276]=275;
    TreeTable[277]=236;
    TreeTable[278]=277;
    TreeTable[279]=185;
    TreeTable[280]=254;
    TreeTable[281]=254;
    TreeTable[282]=248;
    TreeTable[283]=235;
    TreeTable[284]=255;
    TreeTable[285]=233;
    TreeTable[286]=233;
    TreeTable[287]=233;
    TreeTable[288]=233;
    TreeTable[289]=246;
    TreeTable[290]=246;
    TreeTable[291]=246;
    TreeTable[292]=246;
    TreeTable[293]=257;
    TreeTable[294]=293;
    TreeTable[295]=293;
    TreeTable[297]=293;
    TreeTable[298]=0;
    TreeTable[299]=0;
    TreeTable[300]=0;
    TreeTable[301]=293;
    TreeTable[302]=184;
    TreeTable[304]=184;
    TreeTable[305]=184;
    TreeTable[306]=184;
    TreeTable[308]=260;
    TreeTable[309]=255;
    TreeTable[314]=243;
    TreeTable[315]=243;
    TreeTable[316]=306;
    TreeTable[322]=0;
    TreeTable[326]=238;
    TreeTable[327]=238;
    TreeTable[329]=238;
    TreeTable[330]=238;
    TreeTable[331]=238;
    TreeTable[332]=238;
    TreeTable[333]=238;
    TreeTable[336]=238; 
    TreeTable[337]=238;
    TreeTable[338]=238;
    TreeTable[339]=306;
    TreeTable[348]=240;
    TreeTable[352]=238;
    TreeTable[353]=240;
    TreeTable[354]=184;
    TreeTable[355]=354;
    TreeTable[356]=354;
    TreeTable[357]=354;
    TreeTable[358]=354;
    TreeTable[359]=354;
    TreeTable[360]=354;
    TreeTable[361]=354;
    TreeTable[362]=354;
    TreeTable[364]=354;
    TreeTable[365]=354;
    TreeTable[366]=354;
    TreeTable[367]=240;
    TreeTable[368]=240;
    TreeTable[369]=279;
 
    haveNoChilds[160]=0;
    haveNoChilds[184]=0;
    haveNoChilds[185]=0;
    haveNoChilds[232]=0;
    haveNoChilds[233]=0;
    haveNoChilds[234]=1;
    haveNoChilds[235]=0;
    haveNoChilds[236]=0;
    haveNoChilds[237]=1;
    haveNoChilds[238]=0;
    haveNoChilds[239]=1;
    haveNoChilds[240]=0;
    haveNoChilds[242]=1;
    haveNoChilds[243]=0;
    haveNoChilds[244]=1;
    haveNoChilds[245]=0;
    haveNoChilds[246]=0;
    haveNoChilds[247]=1;
    haveNoChilds[248]=0;
    haveNoChilds[249]=0;
    haveNoChilds[250]=1;
    haveNoChilds[251]=0;
    haveNoChilds[252]=1;
    haveNoChilds[253]=1;
    haveNoChilds[254]=0;
    haveNoChilds[255]=0;
    haveNoChilds[256]=1;
    haveNoChilds[257]=0;
    haveNoChilds[258]=1;
    haveNoChilds[259]=1;
    haveNoChilds[260]=0;
    haveNoChilds[261]=1;
    haveNoChilds[262]=1;
    haveNoChilds[263]=1;
    haveNoChilds[264]=1;
    haveNoChilds[265]=1;
    haveNoChilds[267]=1;
    haveNoChilds[268]=1;
    haveNoChilds[269]=1;
    haveNoChilds[270]=1;
    haveNoChilds[271]=1;
    haveNoChilds[272]=1;
    haveNoChilds[273]=1;
    haveNoChilds[274]=1;
    haveNoChilds[275]=0;
    haveNoChilds[276]=1;
    haveNoChilds[277]=0;
    haveNoChilds[278]=1;
    haveNoChilds[279]=0;
    haveNoChilds[280]=1;
    haveNoChilds[281]=1;
    haveNoChilds[282]=1;
    haveNoChilds[283]=1;
    haveNoChilds[284]=1;
    haveNoChilds[285]=1;
    haveNoChilds[286]=1;
    haveNoChilds[287]=1;
    haveNoChilds[288]=1;
    haveNoChilds[289]=1;
    haveNoChilds[290]=1;
    haveNoChilds[291]=1;
    haveNoChilds[292]=1;
    haveNoChilds[293]=0;
    haveNoChilds[294]=1;
    haveNoChilds[295]=1;
    haveNoChilds[297]=1;
    haveNoChilds[298]=1;
    haveNoChilds[299]=1;
    haveNoChilds[300]=1;
    haveNoChilds[301]=1;
    haveNoChilds[302]=1;
    haveNoChilds[304]=1;
    haveNoChilds[305]=1;
    haveNoChilds[306]=0;
    haveNoChilds[308]=1;
    haveNoChilds[309]=1;
    haveNoChilds[314]=1;
    haveNoChilds[315]=1;
    haveNoChilds[316]=1;
    haveNoChilds[322]=1;
    haveNoChilds[326]=1;
    haveNoChilds[327]=1;
    haveNoChilds[329]=1;
    haveNoChilds[330]=1;
    haveNoChilds[331]=1;
    haveNoChilds[332]=1;
    haveNoChilds[333]=1;
    haveNoChilds[336]=1;
    haveNoChilds[337]=1;
    haveNoChilds[338]=1;
    haveNoChilds[339]=1;
    haveNoChilds[348]=1;
    haveNoChilds[352]=1;
    haveNoChilds[353]=1;
    haveNoChilds[354]=0;
    haveNoChilds[355]=1;
    haveNoChilds[356]=1;
    haveNoChilds[357]=1;
    haveNoChilds[358]=1;
    haveNoChilds[359]=1;
    haveNoChilds[360]=1;
    haveNoChilds[361]=1;
    haveNoChilds[362]=1;
    haveNoChilds[364]=1;
    haveNoChilds[365]=1;
    haveNoChilds[366]=1;
    haveNoChilds[367]=1;
    haveNoChilds[368]=1;
    haveNoChilds[369]=1;
    
    
    for (i=0; i<tmpTags.length; i++) 
        if (tmpTags[i].id.substring(0,4) == "node") {
            NodeID = tmpTags[i].id.substring(5);
            NodesIndexes[NodesIndexes.length]=NodeID;
            NodesTags[NodeID]=tmpTags[i];
            NodesStatus[NodeID]=0;
            if (TreeTable[NodeID]==0) {
                NodesStatus[NodeID]=0;
                NodesTags[NodeID].style.display = "block";
            }
        }
    
    
}//initTree
 
 
function openNode(NodeID)
	{
	NodesTags[NodeID].style.display = "block";
	if (!haveNoChilds[NodeID])
		eval('document.conerimage_'+NodeID+'.src="templates/{$theme_name}/images/nav_oconer.gif"');
	NodesStatus[NodeID]=1;
	for (i=0;i<TreeTable.length;i++)
		if (TreeTable[i]==NodeID)
			{
			NodesTags[i].style.display = "block";
			if (!haveNoChilds[NodeID])
				eval('document.conerimage_'+NodeID+'.src="templates/{$theme_name}/images/nav_oconer.gif";');
			NodesStatus[i]=0;
			}
	}
 
function closeNode(NodeID)
	{
	var iteration;
	NodesTags[NodeID].style.display = "none";
	if (!haveNoChilds[NodeID])
		eval('document.conerimage_'+NodeID+'.src="templates/{$theme_name}/images/nav_cconer.gif";');
	NodesStatus[NodeID] = 0;
	for (iteration=0;iteration<TreeTable.length;iteration++)
		if (TreeTable[iteration]==NodeID)
				closeNode(iteration);
	}
 
function changeFolder(NodeID)
	{
	if (oldFolder>0)
		{
		NodeNames[oldFolder].style.background = "#FFFFFF";
		NodeNames[oldFolder].style.color = "#000000";
		eval('document.folderimage_'+oldFolder+'.src="templates/{$theme_name}/images/nav_cfolder.gif";');
		}
	if (NodeID>0)
		{
		eval('document.folderimage_'+NodeID+'.src="templates/{$theme_name}/images/nav_ofolder.gif";');
		NodeNames[NodeID].style.background = "#0A246A";
		NodeNames[NodeID].style.color = "#FFFFFF";
		var ind;
		ind=NodeID;
		while (ind=TreeTable[ind])
			if (!NodesStatus[ind]) changeNode(ind);
		}
	oldFolder=NodeID;
	}
 
function openFolder(NodeID)
	{
	
	if (oldFolder>0)
		{
		NodeNames[oldFolder].style.background = "#FFFFFF";
		NodeNames[oldFolder].style.color = "#000000";
		eval('document.folderimage_'+oldFolder+'.src="templates/{$theme_name}/images/nav_cfolder.gif";');
		}
	if (NodeID>0)
		{
		eval('document.folderimage_'+NodeID+'.src="templates/{$theme_name}/images/nav_ofolder.gif";');
		NodeNames[NodeID].style.background = "#0A246A";
		NodeNames[NodeID].style.color = "#FFFFFF";
		
		var ind;
		ind=NodeID;
		while (ind=TreeTable[ind])
			if (!NodesStatus[ind]) changeNode(ind);
		}
	oldFolder=NodeID;
 
	
	}
 
function changeNode(NodeID)
	{
	if (NodesStatus[NodeID])
		{
		for (j=0;j<TreeTable.length;j++)
			if (TreeTable[j]==NodeID)
				closeNode(j);
 
		var needOpen=false;
 
		needOpen=FindElement(NodeID,oldFolder);
		NodesStatus[NodeID]=0;
		eval('document.conerimage_'+NodeID+'.src="templates/{$theme_name}/images/nav_cconer.gif";');
		if (needOpen)
			openFolder(NodeID);
		}
	else
		{
		NodesStatus[NodeID]=1;
		openNode(NodeID);
		}
	}
 
function changeName(NodeID,NewName)
	{
	NodeNames[NodeID].innerHTML='&nbsp;'+NewName+'&nbsp;';
	}
 
function FindElement(startNode,NodeID)
	{
	var EIndex;
	var toret=false;
	for (EIndex=0;EIndex<TreeTable.length;EIndex++)
		if (TreeTable[EIndex]==startNode)
			if (EIndex==NodeID) 
				return true;
			else
				if (FindElement(EIndex,NodeID)) return true;
	return false;
	}
 
</script> 
{$all_lines}
 <!--
<DIV class=node id="node_160"><table border=0 cellspacing=0 cellpadding=0><tr><td><a onClick='changeNode(160);'><img name=conerimage_160 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99 style='padding-top:5px!important;'><nobr><img name=folderimage_160 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0 ><a href=#160 onClick="openFolder(160);" ><span id="nname_160" onDblClick='changeNode(160);' >&nbsp;Головна сторінка &nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_232"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(232);'><img name=conerimage_232 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_232 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#232 onClick="openFolder(232);"><span id="nname_232" onDblClick='changeNode(232);'>&nbsp;Підприємство&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_235"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(235);'><img name=conerimage_235 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_235 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#235 onClick="openFolder(235);"><span id="nname_235" onDblClick='changeNode(235);'>&nbsp;ISO 9001 - 2000&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_283"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_283 src=templates/{$theme_name}/images/nav_lconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_283 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#283 onClick="openFolder(283);"><span id="nname_283" >&nbsp;Сертифікат ISO 9001 - 2000&nbsp;</span></a></nobr></td></tr></table></DIV> 
 
<DIV class=node id="node_234"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_234 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_234 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#234 onClick="openFolder(234);"><span id="nname_234" >&nbsp;Персонал&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_233"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(233);'><img name=conerimage_233 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_233 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#233 onClick="openFolder(233);"><span id="nname_233" onDblClick='changeNode(233);'>&nbsp;ВФ ДПЗД "Укрінтеренерго"&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_286"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_286 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_286 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#286 onClick="openFolder(286);"><span id="nname_286" >&nbsp;Залучення сучасних технологій та обладнання в Україну&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_285"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_285 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_285 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#285 onClick="openFolder(285);"><span id="nname_285" >&nbsp;Управління проектами в галузі електроенергетики&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_287"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_287 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_287 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#287 onClick="openFolder(287);"><span id="nname_287" >&nbsp;Виробництво електричної та теплової енергії&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_288"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_288 src=templates/{$theme_name}/images/nav_lconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_288 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#288 onClick="openFolder(288);"><span id="nname_288" >&nbsp;Експорт та транзит електроенергії&nbsp;</span></a></nobr></td></tr></table></DIV> 
 
 
<DIV class=node id="node_236"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(236);'><img name=conerimage_236 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_236 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#236 onClick="openFolder(236);"><span id="nname_236" onDblClick='changeNode(236);'>&nbsp;Новини&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_277"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(277);'><img name=conerimage_277 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_277 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#277 onClick="openFolder(277);"><span id="nname_277" onDblClick='changeNode(277);'>&nbsp;Розсилка новин&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_278"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_278 src=templates/{$theme_name}/images/nav_lconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_278 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#278 onClick="openFolder(278);"><span id="nname_278" >&nbsp;Текст розсилки&nbsp;</span></a></nobr></td></tr></table></DIV> 
 
 
<DIV class=node id="node_237"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_237 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_237 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#237 onClick="openFolder(237);"><span id="nname_237" >&nbsp;Діяльність&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_238"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(238);'><img name=conerimage_238 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_238 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#238 onClick="openFolder(238);"><span id="nname_238" onDblClick='changeNode(238);'>&nbsp;Проекти&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_329"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_329 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_329 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#329 onClick="openFolder(329);"><span id="nname_329" >&nbsp;Дністровська ГЕС&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_337"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_337 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_337 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#337 onClick="openFolder(337);"><span id="nname_337" >&nbsp;Ташлицька ГАЕС&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_326"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_326 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_326 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#326 onClick="openFolder(326);"><span id="nname_326" >&nbsp;Слов'янська ТЕС&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_327"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_327 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_327 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#327 onClick="openFolder(327);"><span id="nname_327" >&nbsp;Бурштинська ТЕС&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_352"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_352 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_352 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#352 onClick="openFolder(352);"><span id="nname_352" >&nbsp;ГЕС "Тхак Ба"&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_331"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_331 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_331 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#331 onClick="openFolder(331);"><span id="nname_331" >&nbsp;ГЕС "Шрок Фу Мієнг"&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_332"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_332 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_332 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#332 onClick="openFolder(332);"><span id="nname_332" >&nbsp;ГЕС "Кан Дон"&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_333"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_333 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_333 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#333 onClick="openFolder(333);"><span id="nname_333" >&nbsp;ГЕС "Ялі"&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_330"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_330 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_330 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#330 onClick="openFolder(330);"><span id="nname_330" >&nbsp;ГЕС "Тхакмо"&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_336"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_336 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_336 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#336 onClick="openFolder(336);"><span id="nname_336" >&nbsp;ЛЕП - 500&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_338"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_338 src=templates/{$theme_name}/images/nav_lconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_338 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#338 onClick="openFolder(338);"><span id="nname_338" >&nbsp;Постачання електроенергії до країн Європи&nbsp;</span></a></nobr></td></tr></table></DIV> 
 
<DIV class=node id="node_242"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_242 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_242 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#242 onClick="openFolder(242);"><span id="nname_242" >&nbsp;Партнери&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_243"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(243);'><img name=conerimage_243 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_243 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#243 onClick="openFolder(243);"><span id="nname_243" onDblClick='changeNode(243);'>&nbsp;Контакти&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_315"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_315 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_315 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#315 onClick="openFolder(315);"><span id="nname_315" >&nbsp;Карта проїзду&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_314"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_314 src=templates/{$theme_name}/images/nav_lconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_314 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#314 onClick="openFolder(314);"><span id="nname_314" >&nbsp;Відділи та Представництва&nbsp;</span></a></nobr></td></tr></table></DIV> 
 
<DIV class=node id="node_244"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_244 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_244 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#244 onClick="openFolder(244);"><span id="nname_244" >&nbsp;Карта сайту&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_239"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_239 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_239 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#239 onClick="openFolder(239);"><span id="nname_239" >&nbsp;Презентації&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_240"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><a onClick='changeNode(240);'><img name=conerimage_240 src=templates/{$theme_name}/images/nav_cconer.gif width=20 height=20 border=0></a></td><td colspan=99><nobr><img name=folderimage_240 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#240 onClick="openFolder(240);"><span id="nname_240" onDblClick='changeNode(240);'>&nbsp;Державні закупівлі&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_368"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_368 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_368 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#368 onClick="openFolder(368);"><span id="nname_368" >&nbsp;2009&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_367"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_367 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_367 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#367 onClick="openFolder(367);"><span id="nname_367" >&nbsp;2008&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_348"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_348 src=templates/{$theme_name}/images/nav_sconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_348 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#348 onClick="openFolder(348);"><span id="nname_348" >&nbsp;Річні плани&nbsp;</span></a></nobr></td></tr></table></DIV> 
<DIV class=node id="node_353"><table border=0 cellspacing=0 cellpadding=0><tr><td><img src=templates/{$theme_name}/images/nav_line.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img src=templates/{$theme_name}/images/nav_empty.gif width=20 height=20 border=0 align='absmiddle' alt=''></td><td><img name=conerimage_353 src=templates/{$theme_name}/images/nav_lconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_353 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#353 onClick="openFolder(353);"><span id="nname_353" >&nbsp;Реєстр державних закупівель&nbsp;</span></a></nobr></td></tr></table></DIV> 
 
 
<DIV class=node id="node_298"><table border=0 cellspacing=0 cellpadding=0><tr><td><img name=conerimage_298 src=templates/{$theme_name}/images/nav_lconer.gif width=20 height=20 border=0></td><td colspan=99><nobr><img name=folderimage_298 src=templates/{$theme_name}/images/nav_cfolder.gif width=20 height=20  align="absmiddle" border=0><a href=#298 onClick="openFolder(298);"><span id="nname_298" >&nbsp;Пошук&nbsp;</span></a></nobr></td></tr></table></DIV> 
 -->
</form> 
<br /><br />
</div>
<!-- aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa -->
			</div>
		</div>
		<div class='clr'></div>
	</div>
	<div class='b'>
		<div class='b'>
			<div class='b'></div>
		</div>
	</div>
</div>