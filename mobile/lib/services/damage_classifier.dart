import 'dart:developer';
import 'dart:io';
import 'dart:typed_data';
import 'package:flutter/foundation.dart';
import 'package:flutter/services.dart';
import 'package:image_picker/image_picker.dart';
import 'package:image/image.dart' as img;
import 'package:tflite_flutter/tflite_flutter.dart';

class ClassificationResult {
  final String damageLevel;
  final double confidence;

  ClassificationResult(this.damageLevel, this.confidence);
}

class DamageClassifier {
  Interpreter? _interpreter;
  List<String> _labels = ['ringan', 'sedang', 'berat'];
  bool _isInit = false;

  Future<void> initModel() async {
    if (kIsWeb) {
      log("Fitur TFLite tidak didukung di Web. Harap gunakan Android/iOS.");
      return;
    }
    
    try {
      // Load model
      _interpreter = await Interpreter.fromAsset('assets/ml/road_damage_model.tflite');
      
      // Load labels secara dinamis dari file assets menggunakan rootBundle
      final labelsString = await rootBundle.loadString('assets/ml/labels.txt');
      final labelsData = labelsString.split('\n').map((s) => s.trim()).where((s) => s.isNotEmpty).toList();
      
      if (labelsData.isNotEmpty) {
        _labels = labelsData;
      }
      
      log("Model TFLite & Labels berhasil diload. Labels: $_labels");
      _isInit = true;
    } catch (e) {
      log("Gagal load model TFLite atau Labels: $e");
    }
  }

  Future<ClassificationResult> classifyImage(XFile imageFile) async {
    if (kIsWeb || !_isInit || _interpreter == null) {
      // Fallback jika di web atau model blm ready tapi user tetap mencoba
      log("Fallback ke mock classification karena model tidak ready / dijalankan di browser.");
      await Future.delayed(const Duration(seconds: 1));
      return ClassificationResult('sedang', 0.85); // Deterministic fallback
    }

    try {
      // Load image
      final File file = File(imageFile.path);
      final img.Image? image = img.decodeImage(await file.readAsBytes());
      if (image == null) throw Exception("Failed to decode image");

      // Resize ke dimensi yg diharapkan (misal 224x224 untuk MobileNetV2)
      final img.Image resizedImage = img.copyResize(image, width: 224, height: 224);

      // Preprocess image to float32 tensor
      // MobileNetV2 biasanya mengharapkan tensor 1x224x224x3 dengan range [-1, 1] atau [0, 1]
      // Kita pakai [0, 1] sebagai default umum
      var input = List.generate(1, (i) => List.generate(224, (j) => List.generate(224, (k) => List.filled(3, 0.0))));
      
      for (int y = 0; y < 224; y++) {
        for (int x = 0; x < 224; x++) {
          final pixel = resizedImage.getPixel(x, y);
          input[0][y][x][0] = pixel.r / 255.0; // r
          input[0][y][x][1] = pixel.g / 255.0; // g
          input[0][y][x][2] = pixel.b / 255.0; // b
        }
      }

      // Output shape untuk 3 class adalah [1, 3]
      var output = List.generate(1, (i) => List.filled(3, 0.0));

      // Run inference
      _interpreter!.run(input, output);

      final probabilities = output[0];
      int maxIndex = 0;
      double maxProb = probabilities[0];
      
      for (int i = 1; i < probabilities.length; i++) {
        if (probabilities[i] > maxProb) {
          maxProb = probabilities[i];
          maxIndex = i;
        }
      }

      String predictedLabel = _labels[maxIndex % _labels.length];
      return ClassificationResult(predictedLabel, maxProb);

    } catch (e) {
      log("Error during AI inference: $e");
      // Fallback in case of an error
      return ClassificationResult('sedang', 0.85); 
    }
  }

  void dispose() {
    _interpreter?.close();
  }
}
