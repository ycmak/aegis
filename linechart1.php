<?php
    // include graphpite classes
    include("Graph.php");

    function XtoYear($Value) {
        return $Value+1998;
    }

    function salaries($Value) {
        // I wish!
        return exp($Value)+1000;
    }

    // create the graph as a 500 x 300 image
    $Graph =& new Image_Graph(500, 300);      

    // create a random dataset to use for demonstrational purposes
    $DataSet =& new Image_Graph_Dataset_Function(1, 9, "salaries", 9);
    
    // create the title font
    $Font =& $Graph->addFont(new Image_Graph_Font_TTF("arial.ttf"));
    $Font->setSize(11);       

    // create the title font
    $VerticalFont =& $Graph->addFont(new Image_Graph_Font_Vertical());
    $Font->setSize(11);
    
    // add a plot area in a vertical layout to display a title on top 
    $Graph->add(
        new Image_Graph_Layout_Vertical(
            new Image_Graph_Title("Annual income", $Font),
            $Plotarea = new Image_Graph_Plotarea(),
            5
        ),
        5
    );
    
    //
    $Grid =& $Plotarea->addGridY(new Image_Graph_Grid_Bars());
    $Grid->setFillStyle(new Image_Graph_Fill_Gradient(IMAGE_GRAPH_GRAD_VERTICAL, IMAGE_GRAPH_WHITE, IMAGE_GRAPH_LIGHTGRAY, 100));       

    // add the line plot to the plotarea
    $Plot =& $Plotarea->addPlot(new Image_Graph_Plot_Line($DataSet), "Plot");
    
    // add coins-icon as marker
    $Plot->setMarker(new Image_Graph_Marker_Icon("./images/coins.png"));
    
    $AxisX =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_X);
    $AxisY =& $Plotarea->getAxis(IMAGE_GRAPH_AXIS_Y);
    // make x-axis start at 0
    $AxisX->forceMinimum(0);
    
    // make x-axis end at 11
    $AxisX->forceMaximum(9);
    
    // show axis arrows
    $AxisX->showArrow();  
    $AxisY->showArrow();
    
    // create a datapreprocessor to map X-values to years
    $AxisX->setDataPreprocessor(new Image_Graph_DataPreprocessor_Function("XtoYear"));
    $AxisY->setDataPreprocessor(new Image_Graph_DataPreprocessor_Currency("US$"));    
    // output the graph
    $Graph->done();
?>